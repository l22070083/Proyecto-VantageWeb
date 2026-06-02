/**
 * EStantes
 */
import { supabase } from '../supabaseClient.js';

/**
 * EStantes existentes
 */
export async function getEstantes() {
    try {
        const { data, error } = await supabase
            .from('estantes')
            .select(`
                *,
                inventario_actual(*, productos(*)),
                celdas(*, productos(*))
            `)
            .order('nombre', { ascending: true });

        if (error) throw error;
        return data || [];
    } catch (error) {
        console.error("Error obteniendo estantes:", error);
        return [];
    }
}

/**
 * Registra un nuevo estante 
 */
export async function registerEstante(macAddress, ubicacion) {
    try {
        const mac = macAddress.toUpperCase().trim();
        const { data, error } = await supabase
            .from('estantes')
            .insert([{
                nombre: `Estante ${mac.slice(-5)}`,
                mac_address: mac,
                ubicacion_fisica: ubicacion.trim(),
                esta_abierto: false,
                alerta_activa: false,
                ultima_conexion: new Date().toISOString()
            }])
            .select()
            .single();

        if (error) {
            if (error.code === '23505') {
                throw new Error(`La dirección MAC "${mac}" ya está registrada.`);
            }
            throw error;
        }
        return data;
    } catch (error) {
        console.error("Error en registerEstante:", error);
        throw error;
    }
}

/**
 * Asigna un producto a un estante 
 */
export async function linkProductToShelf(estanteId, productoId) {
    try {
        // Eliminar vínculos previos 
        await supabase
            .from('inventario_actual')
            .delete()
            .eq('estante_id', estanteId);

        // Insertar nuevo vínculo
        const { error: invError } = await supabase
            .from('inventario_actual')
            .insert([{
                estante_id: estanteId,
                producto_id: productoId,
                peso_total_gramos: 0.0,
                cantidad_calculada: 0,
                ultima_actualizacion: new Date().toISOString()
            }]);

        if (invError) throw invError;

        // Limpiar vínculos previos 
        await supabase
            .from('celdas')
            .delete()
            .eq('estante_id', estanteId);

        // Insertar la celda correspondiente
        const { error: celdaError } = await supabase
            .from('celdas')
            .insert([{
                estante_id: estanteId,
                producto_id: productoId,
                max_capacidad: 100
            }]);

        if (celdaError) throw celdaError;

        return true;
    } catch (error) {
        console.error("Error vinculando producto a estante:", error);
        throw error;
    }
}

/**
 * Desvincula cualquier producto de un estante
 */
export async function unlinkProductFromShelf(estanteId, productoId) {
    try {
        // Eliminar de inventario_actual
        const { error: invError } = await supabase
            .from('inventario_actual')
            .delete()
            .eq('estante_id', estanteId)
            .eq('producto_id', productoId);

        if (invError) throw invError;

        // Desvincular de la celda de carga 
        const { error: celdaError } = await supabase
            .from('celdas')
            .delete()
            .eq('estante_id', estanteId);

        if (celdaError) throw celdaError;

        return true;
    } catch (error) {
        console.error("Error desvinculando producto de estante:", error);
        throw error;
    }
}

/**
 * Elimina un estante físicamente
 */
export async function deleteEstante(id) {
    try {
        // Eliminar cascadas si no están configuradas en DB
        await supabase.from('inventario_actual').delete().eq('estante_id', id);
        await supabase.from('celdas').delete().eq('estante_id', id);

        const { error } = await supabase
            .from('estantes')
            .delete()
            .eq('id', id);

        if (error) throw error;
        return true;
    } catch (error) {
        console.error("Error eliminando estante:", error);
        throw error;
    }
}
