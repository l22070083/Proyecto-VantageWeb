<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Registro - Vantage';
$this->context->layout = false; // Desactiva el layout por defecto
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
            
            <div id="auth-view-register" style="display: flex; flex-direction: column; width: 100%; max-width: 500px; padding-bottom: 40px;">
                <a href="<?= Url::to(['site/welcome']) ?>" class="btn" style="background: transparent; color: #2563EB; display: flex; align-items: center; gap: 8px; padding: 0; margin-bottom: 24px; align-self: flex-start; text-decoration: none;">
                    <ion-icon name="arrow-back-outline"></ion-icon> Regresar
                </a>
                
                <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 8px; color: var(--text-color);">Crear una Cuenta</h2>
                <p style="color: var(--text-muted); margin-bottom: 32px;">Completa el formulario para registrarte en Vantage</p>
                
                <div id="register-error" class="hidden" style="background: var(--danger-bg); color: var(--danger); padding: 12px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; text-align: center;"></div>
                
                <form id="register-form">
                    <div class="form-group mb-4">
                        <label style="color: var(--text-color); font-weight: 500; margin-bottom: 8px; display: block;">Nombre de Usuario *</label>
                        <input type="text" id="reg-username" required class="form-control" placeholder="Ej. juan.perez" style="padding: 12px;">
                    </div>
                    
                    <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <label style="color: var(--text-color); font-weight: 500; margin-bottom: 8px; display: block;">Primer Nombre *</label>
                            <input type="text" id="reg-fname" required class="form-control" placeholder="Ej. Juan" style="padding: 12px;">
                        </div>
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <label style="color: var(--text-color); font-weight: 500; margin-bottom: 8px; display: block;">Segundo Nombre</label>
                            <input type="text" id="reg-mname" class="form-control" placeholder="Opcional" style="padding: 12px;">
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <label style="color: var(--text-color); font-weight: 500; margin-bottom: 8px; display: block;">Primer Apellido *</label>
                            <input type="text" id="reg-lname1" required class="form-control" placeholder="Ej. Pérez" style="padding: 12px;">
                        </div>
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <label style="color: var(--text-color); font-weight: 500; margin-bottom: 8px; display: block;">Segundo Apellido *</label>
                            <input type="text" id="reg-lname2" required class="form-control" placeholder="Ej. García" style="padding: 12px;">
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label style="color: var(--text-color); font-weight: 500; margin-bottom: 8px; display: block;">Contraseña *</label>
                        <input type="password" id="reg-password" required class="form-control" placeholder="Mínimo 6 caracteres" style="padding: 12px;">
                    </div>
                    
                    <button type="submit" id="btn-register-submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 16px; margin-top: 16px;">Completar Registro</button>
                </form>
            </div>
        </div>
    </div>
    
    <script type="module">
        import { register, getSession } from '<?= Url::base() ?>/js/services/authService.js';

        // Redirigir si ya hay sesión
        document.addEventListener('DOMContentLoaded', async () => {
            const session = await getSession();
            if (session) {
                window.location.href = '<?= Url::to(['site/index']) ?>';
            }
        });

        const registerForm = document.getElementById('register-form');
        const registerError = document.getElementById('register-error');

        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('reg-username').value;
            const fname = document.getElementById('reg-fname').value;
            const mname = document.getElementById('reg-mname').value;
            const lname1 = document.getElementById('reg-lname1').value;
            const lname2 = document.getElementById('reg-lname2').value;
            const password = document.getElementById('reg-password').value;
            const btn = document.getElementById('btn-register-submit');
            
            registerError.classList.add('hidden');
            
            if (password.length < 6) {
                registerError.textContent = "La contraseña debe tener mínimo 6 caracteres.";
                registerError.classList.remove('hidden');
                return;
            }
            
            btn.innerHTML = 'Cargando...';
            btn.disabled = true;
            
            try {
                const fullName = `${fname} ${mname} ${lname1} ${lname2}`.replace(/\s+/g, ' ').trim();
                // Asignamos por defecto el rol "Empleado" (ID 4)
                await register(username, password, fullName, { role_id: 4 });
                alert("Cuenta creada exitosamente. Ya puedes iniciar sesión.");
                window.location.href = '<?= Url::to(['site/login']) ?>';
            } catch (error) {
                registerError.textContent = error.message;
                registerError.classList.remove('hidden');
            } finally {
                btn.innerHTML = 'Completar Registro';
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>