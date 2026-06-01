/**
 * Servicio de Auth
 */
import { supabase } from '../supabaseClient.js';

export async function login(username, password) {
    try {
        const sanitizedUsername = username.trim().toLowerCase();
        const email = `${sanitizedUsername}@vantage.com`;

        const { data, error } = await supabase.auth.signInWithPassword({
            email: email,
            password: password,
        });

        if (error) throw error;
        return data.session;
    } catch (error) {
        console.error("Error en login:", error.message);
        throw error;
    }
}

export async function register(username, password, fullName, metaData = {}) {
    try {
        const sanitizedUsername = username.trim().toLowerCase();
        const email = `${sanitizedUsername}@vantage.com`;

        const { data, error } = await supabase.auth.signUp({
            email: email,
            password: password,
            options: {
                data: {
                    full_name: fullName,
                    username: sanitizedUsername,
                    ...metaData
                }
            }
        });

        if (error) throw error;
        return data;
    } catch (error) {
        console.error("Error en registro:", error.message);
        throw error;
    }
}

export async function logout() {
    try {
        await supabase.auth.signOut();
    } catch (error) {
        console.error("Error cerrando sesión:", error);
    }
}

export async function getSession() {
    const { data, error } = await supabase.auth.getSession();
    if (error) throw error;
    return data.session;
}
