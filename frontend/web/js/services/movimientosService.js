/**
 * Vista funcional de movimientos
 */
import { supabase } from '../supabaseClient.js';

/**
 * Lista de movimientos
 */
export async function getMovimientos(limit = 50) {
    try {
        const { data, error } = await supabase
            .from('historial_movimientos')
            .select('*, productos(nombre), estantes(nombre)')
            .order('fecha_hora', { ascending: false })
            .limit(limit);

        if (error) throw error;
        return data || [];
    } catch (error) {
        console.error("Error obteniendo movimientos:", error);
        return [];
    }
}

/**
 * Información de los movimientos
 */
export async function getMovimientosStatistics() {
    try {
        const { data, error } = await supabase
            .from('historial_movimientos')
            .select('tipo_accion');

        if (error) throw error;

        const stats = {
            total: data.length,
            entradas: data.filter(m => m.tipo_accion === 'ENTRADA').length,
            salidas: data.filter(m => m.tipo_accion === 'SALIDA').length
        };
        return stats;
    } catch (error) {
        console.error("Error obteniendo estadísticas:", error);
        return { total: 0, entradas: 0, salidas: 0 };
    }
}

/**
 * Stock actual del producto
 */
export async function getStockActual(productoId) {
    try {
        const { data, error } = await supabase
            .from('productos')
            .select('stock_minimo')
            .eq('id', productoId)
            .single();

        if (error) throw error;
        return data ? (data.stock_minimo || 0) : 0;
    } catch (error) {
        console.error("Error obteniendo stock actual:", error);
        return 0;
    }
}

/**
 * Actualización de stock al registrar movimientos
 */
export async function createMovimiento(input) {
    try {
        // Usuario activo
        const { data: { user } } = await supabase.auth.getUser();
        if (!user) {
            throw new Error("No hay una sesión activa de usuario.");
        }

        // Perfil del usuario
        let usuarioNombre = user.user_metadata?.full_name || "Mi Perfil";
        let usuarioUsername = user.user_metadata?.username ? `@${user.user_metadata.username}` : "";
        let usuarioRol = "Empleado";

        const rolesMap = {
            1: "Administrador TI",
            2: "Desarrollador",
            3: "Tester",
            4: "Empleado"
        };
        if (user.user_metadata?.role_id) {
            usuarioRol = rolesMap[user.user_metadata.role_id] || "Empleado";
        }

        const { data: perfilData } = await supabase
            .from('perfiles')
            .select('nombre_completo, roles(nombre)')
            .eq('id', user.id)
            .single();

        if (perfilData) {
            usuarioNombre = perfilData.nombre_completo || usuarioNombre;
            usuarioRol = perfilData.roles?.nombre || usuarioRol;
        }

        // 2. Obtener producto y validar stock para SALIDAS
        const { data: producto, error: prodErr } = await supabase
            .from('productos')
            .select('nombre, stock_minimo')
            .eq('id', input.producto_id)
            .single();

        if (prodErr || !producto) {
            throw new Error("El producto seleccionado no existe.");
        }

        const stockActual = producto.stock_minimo || 0;
        if (input.tipo_accion === "SALIDA" && input.cantidad > stockActual) {
            throw new Error(`Stock insuficiente. Disponible: ${stockActual} unidades.`);
        }

        // Calcular diferencia de peso
        const diferenciaPesoGramos = input.tipo_accion === "SALIDA"
            ? -(input.cantidad * input.peso_individual_gramos)
            : (input.cantidad * input.peso_individual_gramos);

        // Crear el registro de movimiento
        const { data: movimiento, error: movErr } = await supabase
            .from('historial_movimientos')
            .insert([{
                usuario_id: user.id,
                estante_id: (input.estante_id && input.estante_id !== "") ? input.estante_id : null,
                producto_id: input.producto_id,
                tipo_accion: input.tipo_accion,
                diferencia_peso_gramos: diferenciaPesoGramos,
                cantidad: input.cantidad,
                fecha_hora: new Date().toISOString(),
                usuario_nombre: usuarioNombre,
                usuario_rol: usuarioRol,
                usuario_username: usuarioUsername
            }])
            .select()
            .single();

        if (movErr) throw movErr;

        // Actualizar el stock actual
        const delta = input.tipo_accion === "ENTRADA" ? input.cantidad : -input.cantidad;
        const nuevoStock = Math.max(0, stockActual + delta);

        const { error: stockErr } = await supabase
            .from('productos')
            .update({ stock_minimo: nuevoStock })
            .eq('id', input.producto_id);

        if (stockErr) throw stockErr;

        // SActualizar la cantidad calculada
        if (input.estante_id) {
            const { data: invData } = await supabase
                .from('inventario_actual')
                .select('peso_total_gramos, cantidad_calculada')
                .eq('estante_id', input.estante_id)
                .single();

            const pesoEstanteActual = invData ? (invData.peso_total_gramos || 0) : 0;
            const nuevoPesoEstante = Math.max(0, pesoEstanteActual + diferenciaPesoGramos);
            const nuevaCantEstante = Math.max(0, Math.round(nuevoPesoEstante / input.peso_individual_gramos));

            await supabase
                .from('inventario_actual')
                .update({
                    peso_total_gramos: nuevoPesoEstante,
                    cantidad_calculada: nuevaCantEstante,
                    ultima_actualizacion: new Date().toISOString()
                })
                .eq('estante_id', input.estante_id);
        }

        return movimiento;
    } catch (error) {
        console.error("Error registrando movimiento:", error);
        throw error;
    }
}

/**
 * Eliminar un movimiento
 */
export async function deleteMovimientoService(id) {
    try {
        const { error } = await supabase
            .from('historial_movimientos')
            .delete()
            .eq('id', id);

        if (error) throw error;
        return true;
    } catch (error) {
        console.error("Error eliminando movimiento:", error);
        return false;
    }
}
