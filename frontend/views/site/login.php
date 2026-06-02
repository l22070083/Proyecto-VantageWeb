<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Iniciar Sesión - Vantage';
$this->context->layout = false; // Desactiva el layout por defecto para que la vista renderice con su propio diseño
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->title ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/css/vantage.css?v=3">
    
    <!-- Conexión a Supabase -->
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <div class="auth-container" style="background: var(--bg-color); min-height: 100vh; display: flex;">
        
        <div style="flex: 1; background: linear-gradient(135deg, #0F172A 0%, #134E4A 100%); display: none; flex-direction: column; justify-content: center; align-items: center; padding: 40px; color: white; @media(min-width: 768px) { display: flex; }">
            <img src="/images/vantage.png" alt="Vantage Logo" style="width: 200px; height: 200px; border-radius: 32px; margin-bottom: 32px; object-fit: contain; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);">
            <h1 style="font-size: 48px; font-weight: 800; margin-bottom: 16px; letter-spacing: -1px;">VANTAGE</h1>
            <p style="font-size: 20px; color: #CCFBF1; max-width: 400px; text-align: center;">Sistema de gestión de inventarios e integración IoT para almacenes inteligentes.</p>
        </div>

        <!-- Formularios -->
        <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; background: var(--bg-color); padding: 40px; overflow-y: auto;">
            
            <!-- Login -->
            <div id="auth-view-login" style="display: flex; flex-direction: column; width: 100%; max-width: 400px;">
                
                <a href="<?= Url::to(['site/index']) ?>" class="btn" style="background: transparent; color: #2563EB; display: flex; align-items: center; gap: 8px; padding: 0; margin-bottom: 32px; align-self: flex-start; text-decoration: none;">
                    <ion-icon name="arrow-back-outline"></ion-icon> Regresar
                </a>
                
                <img src="/images/vantage.png" alt="Vantage Logo" style="width: 100px; height: 100px; border-radius: 16px; margin-bottom: 24px; object-fit: contain; display: block; @media(min-width: 768px) { display: none; }">
                
                <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 8px; color: var(--text-color);">Iniciar Sesión</h2>
                <p style="color: var(--text-muted); margin-bottom: 32px;">Ingresa tus credenciales para acceder</p>
                
                <div id="auth-error" class="hidden" style="background: var(--danger-bg); color: var(--danger); padding: 12px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; text-align: center;"></div>
                
                <form id="login-form">
                    <div class="form-group mb-4">
                        <label style="color: var(--text-color); font-weight: 500; margin-bottom: 8px; display: block;">Nombre de Usuario</label>
                        <input type="text" id="login-username" required class="form-control" placeholder="Ej. admin" style="padding: 14px; font-size: 15px;">
                    </div>
                    <div class="form-group mb-4">
                        <label style="color: var(--text-color); font-weight: 500; margin-bottom: 8px; display: block;">Contraseña</label>
                        <input type="password" id="login-password" required class="form-control" placeholder="••••••••" style="padding: 14px; font-size: 15px;">
                    </div>
                    <button type="submit" id="btn-login-submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 16px; margin-top: 8px; margin-bottom: 16px;">Entrar</button>
                </form>
                
                <div style="text-align: center; margin-top: 16px;">
                    <p style="color: var(--text-muted); font-size: 14px;">¿No tienes cuenta? <a href="<?= Url::to(['site/register']) ?>" style="color: #2563EB; font-weight: 600; cursor: pointer; font-size: 14px; text-decoration: none;">Crear Cuenta</a></p>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        import { login } from '<?= Url::base() ?>/js/services/authService.js';

        const loginForm = document.getElementById('login-form');
        const authError = document.getElementById('auth-error');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('login-username').value;
            const password = document.getElementById('login-password').value;
            const btn = document.getElementById('btn-login-submit');
            
            authError.classList.add('hidden');
            btn.innerHTML = 'Cargando...';
            btn.disabled = true;
            
            try {
                await login(username, password);
                window.location.href = '<?= Url::to(['site/index']) ?>'; 
            } catch (error) {
                authError.textContent = error.message;
                authError.classList.remove('hidden');
            } finally {
                btn.innerHTML = 'Entrar';
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>
