<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Bienvenido - Vantage';
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

        <!-- Contenido principal -->
        <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; background: var(--bg-color); padding: 40px; overflow-y: auto;">
            
            <div style="display: flex; flex-direction: column; align-items: center; width: 100%; max-width: 400px;">
                <img src="/images/vantage.png" alt="Vantage Logo" style="width: 100px; height: 100px; border-radius: 16px; margin-bottom: 24px; object-fit: contain; display: block; @media(min-width: 768px) { display: none; }">
                <h2 style="font-size: 32px; font-weight: 700; margin-bottom: 8px; text-align: center; color: var(--text-color);">¡Bienvenido!</h2>
                <p style="color: var(--text-muted); text-align: center; margin-bottom: 48px; font-size: 16px;">Selecciona una opción para continuar</p>
                
                <a href="<?= Url::to(['site/login']) ?>" class="btn btn-primary" style="width: 100%; padding: 16px; margin-bottom: 16px; font-size: 16px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none;">
                    <ion-icon name="log-in-outline" style="font-size: 20px;"></ion-icon> Iniciar Sesión
                </a>
                <!-- Enlace temporal a index#register mientras lo extraemos -->
                <a href="<?= Url::to(['site/index', '#' => 'register']) ?>" class="btn btn-success" style="width: 100%; padding: 16px; font-size: 16px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none;">
                    <ion-icon name="person-add-outline" style="font-size: 20px;"></ion-icon> Registrarse
                </a>
            </div>
        </div>
    </div>

    <script type="module">
        import { getSession } from '<?= Url::base() ?>/js/services/authService.js';

        document.addEventListener('DOMContentLoaded', async () => {
            const session = await getSession();
            if (session) window.location.href = '<?= Url::to(['site/index']) ?>'; 
        });
    </script>
</body>
</html>