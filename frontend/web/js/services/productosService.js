/**
 * Productos
 */
import { supabase } from '../supabaseClient.js';

export async function createProducto(input) {
    try {
        const producto = {
            nombre: input.nombre,
            categoria: input.categoria || null,
            peso_unidad: input.peso_unidad || 0,
            stock_minimo: input.stock_minimo || 0
        };

        const { data, error } = await supabase
            .from('productos')
            .insert([producto])
            .select()
            .single();

        if (error) throw error;
        return data;
    } catch (error) {
        console.error("Error creando producto:", error);
        throw error;
    }
}

export async function updateProducto(id, input) {
    try {
        const { data, error } = await supabase
            .from('productos')
            .update(input)
            .eq('id', id)
            .select()
            .single();

        if (error) throw error;
        return data;
    } catch (error) {
        console.error("Error actualizando producto:", error);
        throw error;
    }
}

export async function deleteProducto(id) {
    try {
        const { error } = await supabase
            .from('productos')
            .delete()
            .eq('id', id);

        if (error) throw error;
        return true;
    } catch (error) {
        console.error("Error eliminando producto:", error);
        throw error;
    }
}

export async function getProductos() {
    try {
        const { data, error } = await supabase
            .from('productos')
            .select('*, inventario_actual(cantidad, estantes(nombre))');

        if (error) throw error;
        return data;
    } catch (error) {
        console.error("Error obteniendo productos:", error);
        return [];
    }
}

export async function getProductoStats() {
    try {
        const { count, error } = await supabase
            .from('productos')
            .select('*', { count: 'exact', head: true });

        if (error) throw error;
        return { total: count || 0 };
    } catch (error) {
        console.error("Error obteniendo estadísticas:", error);
        return { total: 0 };
    }
}

export async function getProductLogs() {
    try {
        const { data, error } = await supabase
            .from('historial_cambios_productos')
            .select('*')
            .order('fecha_hora', { ascending: false });

        if (error) throw error;
        return data || [];
    } catch (error) {
        console.error("Error obteniendo historial de cambios de productos:", error);
        return [];
    }
}
