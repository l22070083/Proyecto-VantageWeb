<?php
/* @var $this yii\web\View */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vantage - Sistema de Gestión Premium</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    
    <link rel="stylesheet" href="/css/vantage.css?v=3">
    
    <!-- Conexión a Supabase -->
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- Vista Principal-->
    <div id="app">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="brand">
                <img src="/images/vantage.png" alt="Vantage Logo" style="width: 50px; height: 50px; margin-right: 10px; object-fit: contain;"> Vantage
            </div>
            
            <nav class="nav-menu">
                <a href="#dashboard" class="nav-item active" data-target="dashboard-view">
                    <ion-icon name="grid-outline"></ion-icon>
                    <span>Home</span>
                </a>
                <a href="#productos" class="nav-item" data-target="productos-view">
                    <ion-icon name="pricetags-outline"></ion-icon>
                    <span>Productos</span>
                </a>
                <a href="#iot" class="nav-item" data-target="iot-view">
                    <ion-icon name="hardware-chip-outline"></ion-icon>
                    <span>Estantes</span>
                </a>
                <a href="#historial" class="nav-item" data-target="historial-view">
                    <ion-icon name="list-outline"></ion-icon>
                    <span>Movimientos</span>
                </a>
                <a href="#perfil" class="nav-item" data-target="perfil-view">
                    <ion-icon name="person-outline"></ion-icon>
                    <span>Perfil</span>
                </a>
            </nav>
            
            <div style="flex: 1;"></div>
            
            <div class="nav-item" id="logout-btn" style="color: var(--danger-color); cursor: pointer;">
                <ion-icon name="log-out-outline"></ion-icon>
                <span>Cerrar Sesión</span>
            </div>
        </aside>

        <!-- Dashboard -->
        <main class="main-content">
            <header class="header">
                <h2 class="header-title" id="current-view-title">Dashboard</h2>
                
                <div class="user-profile" onclick="window.navigateTo('perfil')" style="cursor: pointer;">
                    <div class="avatar" id="user-avatar">U</div>
                    <span id="user-email" style="font-weight: 500;">Cargando...</span>
                </div>
            </header>

            <div class="page-content" style="max-width: 1000px;">
                <!-- Dashboard View -->
                <?= $this->render('_dashboard') ?>

                <!-- Productos View -->
                <div id="productos-view" class="view-section hidden">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <div>
                            <h2 style="font-size: 28px; font-weight: 700; color: var(--text-color);">Productos</h2>
                            <p style="color: var(--text-muted); font-size: 14px; margin-top: 2px;">Gestión de Productos</p>
                        </div>
                        <button class="btn btn-primary" id="btn-nuevo-producto" style="display: flex; align-items: center; gap: 8px;">
                            <ion-icon name="add-outline" style="font-size: 18px;"></ion-icon> Nuevo Producto
                        </button>
                    </div>
                    
                    <!-- Barra de filtro -->
                    <div style="display: flex; gap: 16px; margin-bottom: 24px; align-items: center; flex-wrap: wrap;">
                        <div class="stat-chip" style="flex: 0 0 200px; background-color: var(--primary-light); align-items: flex-start; padding: 16px; border-radius: var(--radius-lg);">
                            <p class="label" style="color: var(--primary-color); font-size: 12px;">Total Productos</p>
                            <p id="prod-total-badge" class="value" style="color: var(--primary-color); font-size: 32px; font-weight: 800; margin-top: 4px;">0</p>
                        </div>
                        
                        <div class="card" style="flex: 1; margin-bottom: 0; padding: 16px; display: flex; align-items: center; gap: 16px; width: 100%;">
                            <div style="flex: 1; position: relative;">
                                <input type="text" id="productos-search" class="form-control" placeholder="Buscar por nombre..." style="padding-left: 40px;" oninput="loadProductosList()">
                                <ion-icon name="search-outline" style="position: absolute; left: 14px; top: 14px; color: var(--text-secondary); font-size: 18px;"></ion-icon>
                            </div>
                            
                            <div style="display: flex; background: var(--surface-alt); padding: 4px; border-radius: 8px; gap: 4px;">
                                <button type="button" class="btn" id="filter-prod-todos" onclick="setProductFilter('todos')" style="padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; background: var(--surface); color: var(--text-color); border: none; cursor: pointer;">Todos</button>
                                <button type="button" class="btn" id="filter-prod-historial" onclick="setProductFilter('historial')" style="padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; background: transparent; color: var(--text-secondary); border: none; cursor: pointer;">Historial de Cambios</button>
                            </div>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div id="productos-todos-container" class="card no-padding">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Peso Unidad</th>
                                        <th>Stock actual</th>
                                        <th>Calibración Celda</th>
                                        <th style="text-align: right;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="productos-table">
                                    <tr><td colspan="6" style="text-align: center; padding: 24px; color: var(--text-secondary);">Cargando productos...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="productos-historial-container" class="hidden" style="display: flex; flex-direction: column; gap: 16px; width: 100%;">
                        
                    </div>
                </div>

                <!-- Vista de Estantes-->
                <div id="iot-view" class="view-section hidden">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <div>
                            <h2 style="font-size: 28px; font-weight: 700; color: var(--text-color);">Dispositivos IoT</h2>
                            <p style="color: var(--text-muted); font-size: 14px; margin-top: 2px;">Monitoreo de básculas de peso y control RFID en tiempo real</p>
                        </div>
                        <button class="btn btn-primary" id="btn-nuevo-estante" style="display: flex; align-items: center; gap: 8px;">
                            <ion-icon name="add-outline" style="font-size: 18px;"></ion-icon> Nuevo Estante
                        </button>
                    </div>

                    <div style="display: flex; gap: 24px; align-items: flex-start; flex-wrap: wrap;">
                        
                        <div style="flex: 1 1 500px; display: flex; flex-direction: column; gap: 12px;">
                            <h3 style="font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); margin-bottom: 8px;">Estantes Inteligentes</h3>
                            <div id="iot-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
                                
                            </div>
                        </div>

                        
                        <div style="flex: 0 0 320px; width: 100%; display: flex; flex-direction: column; gap: 12px;">
                            <h3 style="font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); margin-bottom: 8px;">Control de Acceso RFID</h3>
                            <div class="card" style="padding: 20px;">
                                <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 16px;">CONTROL DE ACCESO RFID</p>
                                
                                <div style="display: flex; flex-direction: column; gap: 12px;" id="rfid-profiles-list">
                                    <div style="text-align: center; color: var(--text-muted); padding: 16px;">Cargando perfiles...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vista de Historial -->
                <div id="historial-view" class="view-section hidden">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <div>
                            <h2 style="font-size: 28px; font-weight: 700; color: var(--text-color);">Historial de Movimientos</h2>
                            <p style="color: var(--text-muted); font-size: 14px; margin-top: 2px;">Auditoría completa de entradas, salidas y telemetría de peso.</p>
                        </div>
                        <button class="btn btn-primary" onclick="abrirModalNuevoMovimiento()" style="display: flex; align-items: center; gap: 8px;">
                            <ion-icon name="add-outline" style="font-size: 18px;"></ion-icon> Registrar Movimiento
                        </button>
                    </div>

                    <!-- Statistics chips -->
                    <div style="display: flex; gap: 12px; margin-bottom: 24px;">
                        <div class="stat-chip" style="background-color: var(--surface-alt); align-items: flex-start; padding: 16px; border: 1px solid var(--border-color);">
                            <ion-icon name="list-outline" style="font-size: 18px; color: var(--text-secondary);"></ion-icon>
                            <p id="stats-mov-total" class="value" style="color: var(--text-color); font-size: 24px; font-weight: 700; margin-top: 4px;">0</p>
                            <p class="label" style="color: var(--text-secondary); font-size: 11px;">Total Movimientos</p>
                        </div>
                        <div class="stat-chip" style="background-color: var(--success-bg); align-items: flex-start; padding: 16px;">
                            <ion-icon name="arrow-up-right-outline" style="font-size: 18px; color: var(--success);"></ion-icon>
                            <p id="stats-mov-entradas" class="value" style="color: var(--success); font-size: 24px; font-weight: 700; margin-top: 4px;">0</p>
                            <p class="label" style="color: var(--success); font-size: 11px;">Entradas</p>
                        </div>
                        <div class="stat-chip" style="background-color: var(--danger-bg); align-items: flex-start; padding: 16px;">
                            <ion-icon name="arrow-down-left-outline" style="font-size: 18px; color: var(--danger);"></ion-icon>
                            <p id="stats-mov-salidas" class="value" style="color: var(--danger); font-size: 24px; font-weight: 700; margin-top: 4px;">0</p>
                            <p class="label" style="color: var(--danger); font-size: 11px;">Salidas</p>
                        </div>
                    </div>

                    <!-- Barra de filtro -->
                    <div class="card" style="padding: 16px; display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                        <div style="flex: 1; position: relative;">
                            <input type="text" id="movimientos-search" class="form-control" placeholder="Buscar por producto o estante..." style="padding-left: 40px;" oninput="loadHistorialList()">
                            <ion-icon name="search-outline" style="position: absolute; left: 14px; top: 14px; color: var(--text-secondary); font-size: 18px;"></ion-icon>
                        </div>
                        <div style="width: 150px;">
                            <select id="movimientos-filter-tipo" class="form-control" style="padding: 10px;" onchange="loadHistorialList()">
                                <option value="TODOS">Todos</option>
                                <option value="ENTRADA">Entradas</option>
                                <option value="SALIDA">Salidas</option>
                            </select>
                        </div>
                    </div>

                    <!-- Cards de movimientos -->
                    <div style="display: flex; flex-direction: column; gap: 12px;" id="movimientos-list-container">
                        <!-- Movements loaded dynamically here -->
                    </div>
                </div>

                <!-- Vista de Perfil -->
                <div id="perfil-view" class="view-section hidden">
                    <div style="margin-bottom: 24px;">
                        <h2 style="font-size: 28px; font-weight: 700; color: var(--text-color);">Mi Perfil y Ajustes</h2>
                        <p style="color: var(--text-muted); font-size: 14px; margin-top: 2px;">Administra tu información de cuenta y preferencias del sistema.</p>
                    </div>

                    <!-- Card del Perfil -->
                    <div class="card" style="display: flex; align-items: center; gap: 24px; padding: 24px; margin-bottom: 24px; flex-wrap: wrap;">
                        <div id="perfil-avatar-circle" style="width: 70px; height: 70px; border-radius: 35px; background-color: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; font-weight: 700; box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.2);">
                            U
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <h3 id="perfil-nombre-completo" style="font-size: 20px; font-weight: 700; color: var(--text-color); margin-bottom: 4px;">Cargando...</h3>
                            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                                <span id="perfil-username-tag" style="background: var(--surface-alt); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; color: var(--text-secondary);">@...</span>
                                <span id="perfil-rol-tag" style="background: var(--primary-light); color: var(--primary-color); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">Rol: ...</span>
                                <span id="perfil-email-tag" style="font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 4px;"><ion-icon name="mail-outline"></ion-icon> ...</span>
                            </div>
                        </div>
                        <button class="btn btn-secondary" onclick="abrirModalEditarPerfil()" style="display: flex; align-items: center; gap: 6px; padding: 8px 16px;">
                            <ion-icon name="create-outline" style="font-size: 16px;"></ion-icon> Editar Nombre
                        </button>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-bottom: 24px;">
                        <!-- Ajustes generales -->
                        <div class="card" style="padding: 20px; margin-bottom: 0;">
                            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; color: var(--text-color);"><ion-icon name="settings-outline" style="color: var(--primary-color);"></ion-icon> Ajustes Generales</h3>
                            
                            <div class="form-group mb-4">
                                <label>Región del Servidor</label>
                                <select id="perfil-region" class="form-control" style="padding: 10px;">
                                    <option value="MX">Latinoamérica (MX)</option>
                                    <option value="US">Estados Unidos (US)</option>
                                    <option value="ES">Europa (ES)</option>
                                </select>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p style="font-size: 14px; font-weight: 600; color: var(--text-color);">Sincronización Inteligente</p>
                                    <p style="font-size: 12px; color: var(--text-secondary);">Mantener datos en tiempo real</p>
                                </div>
                                <label style="position: relative; display: inline-block; width: 44px; height: 24px; margin-bottom: 0;">
                                    <input type="checkbox" id="perfil-sync-mobile" checked style="opacity: 0; width: 0; height: 0;">
                                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--primary-color); transition: .3s; border-radius: 24px; border: 1px solid var(--border-color);"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Notis -->
                        <div class="card" style="padding: 20px; margin-bottom: 0;">
                            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; color: var(--text-color);"><ion-icon name="notifications-outline" style="color: var(--success);"></ion-icon> Notificaciones</h3>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                                <div>
                                    <p style="font-size: 14px; font-weight: 600; color: var(--text-color);">Alertas Push</p>
                                    <p style="font-size: 12px; color: var(--text-secondary);">Recibir alertas de bajo stock</p>
                                </div>
                                <label style="position: relative; display: inline-block; width: 44px; height: 24px; margin-bottom: 0;">
                                    <input type="checkbox" id="perfil-push-notif" checked style="opacity: 0; width: 0; height: 0;">
                                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--success); transition: .3s; border-radius: 24px; border: 1px solid var(--border-color);"></span>
                                </label>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p style="font-size: 14px; font-weight: 600; color: var(--text-color);">Correo Diario / Semanal</p>
                                    <p style="font-size: 12px; color: var(--text-secondary);">Resúmenes automáticos de auditoría</p>
                                </div>
                                <label style="position: relative; display: inline-block; width: 44px; height: 24px; margin-bottom: 0;">
                                    <input type="checkbox" id="perfil-email-notif" style="opacity: 0; width: 0; height: 0;">
                                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--border-color); transition: .3s; border-radius: 24px; border: 1px solid var(--border-color);"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Cierre de sesión -->
                    <div class="card" style="padding: 20px; border-color: rgba(239, 68, 68, 0.2); background: rgba(239, 68, 68, 0.02); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                        <div>
                            <p style="font-size: 14px; font-weight: 700; color: var(--danger);">Zona de Seguridad</p>
                            <p style="font-size: 12px; color: var(--text-secondary);">Salir de tu sesión actual y limpiar cookies del almacén.</p>
                        </div>
                        <button class="btn btn-outline" onclick="document.getElementById('logout-btn').click()" style="color: var(--danger); border-color: var(--danger); font-weight: 700; display: flex; align-items: center; gap: 8px;">
                            <ion-icon name="log-out-outline" style="font-size: 18px;"></ion-icon> Cerrar Sesión
                        </button>
                    </div>
                </div>
                
            </div>
        </main>
    </div>

    <!-- Modales -->
    <?= $this->render('_modals') ?>

    <script>
        
        window.spaCurrentUser = null;
        let currentHistoryIndex = 0;
        let appContainer = null;

        window.closeModal = function(id) {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        };

        window.renderView = function(viewName) {
            if (!appContainer) {
                appContainer = document.getElementById('app');
            }

            const appViews = ['dashboard', 'productos', 'iot', 'historial', 'perfil'];

            if (appViews.includes(viewName)) {
                if (!window.spaCurrentUser) {
                    window.navigateTo('welcome', true);
                    return;
                }
                
                appContainer.style.display = 'flex';
                
                
                const navItems = document.querySelectorAll('.nav-menu .nav-item');
                navItems.forEach(item => {
                    const target = item.getAttribute('data-target');
                    if (target === `${viewName}-view`) {
                        item.classList.add('active');
                        const span = item.querySelector('span');
                        if (span) {
                            document.getElementById('current-view-title').textContent = span.textContent;
                        }
                    } else {
                        item.classList.remove('active');
                    }
                });
                
                // Mostrar sección correspondiente
                const viewSections = document.querySelectorAll('.view-section');
                viewSections.forEach(section => {
                    if (section.id === `${viewName}-view`) {
                        section.classList.remove('hidden');
                    } else {
                        section.classList.add('hidden');
                    }
                });
                
                // Disparar evento para cargar datos si el backend está listo
                window.dispatchEvent(new CustomEvent('loadSpaData', { detail: viewName }));
            }
        };

        window.navigateTo = function(viewName, replace = false) {
            if (replace) {
                history.replaceState({ view: viewName, index: currentHistoryIndex }, '', '#' + viewName);
            } else {
                currentHistoryIndex++;
                history.pushState({ view: viewName, index: currentHistoryIndex }, '', '#' + viewName);
            }
            window.renderView(viewName);
        };

        window.goBack = function(fallback) {
            if (currentHistoryIndex > 0) {
                history.back();
            } else {
                window.navigateTo(fallback, true);
            }
        };

        window.addEventListener('popstate', (event) => {
            if (event.state && typeof event.state.index === 'number') {
                currentHistoryIndex = event.state.index;
                window.renderView(event.state.view);
            } else {
                const hash = location.hash.replace('#', '') || 'dashboard';
                window.renderView(hash);
            }
        });
        
        document.addEventListener('DOMContentLoaded', () => {
            const hash = location.hash.replace('#', '') || 'dashboard';
            window.navigateTo(hash, true);
        });
    </script>

    <script type="module">
        import { supabase } from '<?= \yii\helpers\Url::base() ?>/js/supabaseClient.js';
        
        // Importar módulos
        import { getProductos, createProducto, updateProducto, deleteProducto, getProductoStats, getProductLogs } from '<?= \yii\helpers\Url::base() ?>/js/services/productosService.js';
        import { getMovimientos, getMovimientosStatistics, getStockActual, createMovimiento, deleteMovimientoService } from '<?= \yii\helpers\Url::base() ?>/js/services/movimientosService.js';
        import { getEstantes, registerEstante, linkProductToShelf, unlinkProductFromShelf, deleteEstante } from '<?= \yii\helpers\Url::base() ?>/js/services/estantesService.js';
        import { logout, getSession } from '<?= \yii\helpers\Url::base() ?>/js/services/authService.js';

        
        let currentUser = null;
        let activeProductFilter = 'todos';
        let activeMovimientoTipo = 'ENTRADA';
        let rfidCountdown = 10;
        let rfidTimer = null;
        let selectedProfileRfid = null;
        
        const logoutBtn = document.getElementById('logout-btn');
        
        window.supabaseClient = supabase;

        // Escuchar cambios de vista para cargar datos del backend
        window.addEventListener('loadSpaData', (e) => {
            const viewName = e.detail;
            if (viewName === 'dashboard') {
                loadDashboardStats();
                loadDashboardMovimientos();
            } else if (viewName === 'productos') {
                loadProductosList();
            } else if (viewName === 'iot') {
                loadEstantesList();
                loadProfilesRfid();
            } else if (viewName === 'historial') {
                loadHistorialList();
            } else if (viewName === 'perfil') {
                loadPerfilData();
            }
        });

        // Fecha actual
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('es-MX', { weekday: 'long', day: 'numeric', month: 'long' });

        // Inicialización
        async function init() {
            try {
                const session = await getSession();
                
                if (session) {
                    currentUser = session.user;
                    window.spaCurrentUser = session.user;
                    if (currentUser && currentUser.email) {
                        document.getElementById('user-email').textContent = currentUser.email;
                        document.getElementById('user-avatar').textContent = currentUser.email.charAt(0).toUpperCase();
                    }
                    setupRealtimeSubscriptions();
                    
                    const hash = location.hash.replace('#', '') || 'dashboard';
                    window.navigateTo(hash, true);
                } else {
                    currentUser = null;
                    window.spaCurrentUser = null;
                    window.location.href = '<?= \yii\helpers\Url::to(['site/welcome']) ?>';
                return;
                }
                
                // Escuchar cambios de autenticación
                supabase.auth.onAuthStateChange((event, session) => {
                    if (event === 'SIGNED_IN' || event === 'TOKEN_REFRESHED') {
                        currentUser = session.user;
                        window.spaCurrentUser = session.user;
                        
                        // Datos de usuario
                        if(currentUser && currentUser.email) {
                            document.getElementById('user-email').textContent = currentUser.email;
                            document.getElementById('user-avatar').textContent = currentUser.email.charAt(0).toUpperCase();
                        }
                        
                        setupRealtimeSubscriptions();
                        
                        const currentHash = location.hash.replace('#', '');
                        if (!currentHash) {
                            window.navigateTo('dashboard', true);
                        } else {
                            window.renderView(currentHash);
                        }
                    } else if (event === 'SIGNED_OUT') {
                        currentUser = null;
                        window.spaCurrentUser = null;
                    window.location.href = '<?= \yii\helpers\Url::to(['site/welcome']) ?>';
                    }
                });
                
                setupNavigation();
            } catch(e) {
                console.error(e);
            }
        }
        
        // Cerrar sesión
        logoutBtn.addEventListener('click', async () => {
            await logout();
        });
        
        // SIDEBAR
        function setupNavigation() {
            const navItems = document.querySelectorAll('.nav-menu .nav-item');
            navItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    if(item.id === 'logout-btn') return;
                    e.preventDefault();
                    
                    const hash = item.getAttribute('href').replace('#', '');
                    window.navigateTo(hash);
                });
            });
        }
        
        async function loadDashboardStats() {
            const prodStats = await getProductoStats();
            document.getElementById('stat-productos').textContent = prodStats.total;
            
            const productos = await getProductos();
            const bajoStock = productos.filter(p => p.stock_minimo && p.stock_minimo <= 5).length;
            document.getElementById('stat-alertas').textContent = bajoStock;
            
            const movStats = await getMovimientosStatistics();
            document.getElementById('stat-movimientos').textContent = movStats.total;
        }
        
        function formatRelativeTime(dateStr) {
            try {
                const diffMs = new Date().getTime() - new Date(dateStr).getTime();
                const diffSec = Math.floor(diffMs / 1000);
                const diffMin = Math.floor(diffSec / 60);
                const diffHr = Math.floor(diffMin / 60);
                const diffDay = Math.floor(diffHr / 24);
 
                if (diffSec < 60) return 'hace un momento';
                if (diffMin < 60) return `hace ${diffMin} min`;
                if (diffHr < 24) return `hace ${diffHr} hora${diffHr > 1 ? 's' : ''}`;
                return `hace ${diffDay} día${diffDay > 1 ? 's' : ''}`;
            } catch {
                return 'recientemente';
            }
        }
        
        async function loadDashboardMovimientos() {
            const movs = await getMovimientos(4);
            
            const tbody = document.getElementById('dashboard-recent-activity');
            if (!movs || !movs.length) {
                tbody.innerHTML = '<tr><td style="padding: 24px; text-align: center; color: var(--text-muted);">Sin movimientos recientes</td></tr>';
                return;
            }
            
            tbody.innerHTML = movs.map((m, i) => {
                const isEntrada = m.tipo_accion === 'ENTRADA';
                const timeAgo = formatRelativeTime(m.fecha_hora);
                const prodName = m.productos?.nombre || 'Producto';
                
                const dotColor = isEntrada ? '#10b981' : '#ef4444';
                const badgeBg = isEntrada ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)';
                const badgeText = isEntrada ? `+${m.cantidad}` : `-${m.cantidad}`;
                
                const borderBottom = i === movs.length - 1 ? 'none' : '1px solid var(--border-color)';
                
                return `
                    <tr style="border-bottom: ${borderBottom}">
                        <td style="padding: 16px;">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div style="width: 8px; height: 8px; border-radius: 4px; background: ${dotColor};"></div>
                                <div style="flex: 1;">
                                    <p style="font-size: 14px; font-weight: 500; color: var(--text-color); margin-bottom: 2px;">
                                        ${isEntrada ? 'Entrada' : 'Salida'}: ${prodName}
                                    </p>
                                    <p style="font-size: 12px; color: var(--text-muted);">${timeAgo}</p>
                                </div>
                                <div style="background: ${badgeBg}; padding: 4px 8px; border-radius: 4px;">
                                    <span style="color: ${dotColor}; font-size: 12px; font-weight: 500;">${badgeText}</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }
        
        // Catálogo de productos
        const btnNuevoProducto = document.getElementById('btn-nuevo-producto');
        const modalProducto = document.getElementById('modal-producto');
        const formProducto = document.getElementById('form-producto');
        
        btnNuevoProducto.addEventListener('click', () => {
            document.getElementById('modal-producto-title').textContent = 'Nuevo Producto';
            formProducto.reset();
            document.getElementById('prod-id').value = '';
            document.getElementById('prod-error').classList.add('hidden');
            modalProducto.classList.remove('hidden');
        });
 
        formProducto.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('prod-id').value;
            const nombre = document.getElementById('prod-nombre').value;
            const categoria = document.getElementById('prod-categoria').value;
            const peso = document.getElementById('prod-peso').value;
            const stockMin = document.getElementById('prod-stock-minimo').value;
            
            const prodError = document.getElementById('prod-error');
            prodError.classList.add('hidden');
            
            const payload = {
                nombre: nombre,
                categoria: categoria,
                peso_unidad: parseInt(peso),
                stock_minimo: parseInt(stockMin)
            };
            
            try {
                let res;
                if (id) {
                    res = await updateProducto(id, payload);
                } else {
                    res = await createProducto(payload);
                }
                modalProducto.classList.add('hidden');
                loadProductosList();
                loadDashboardStats();
            } catch (err) {
                prodError.textContent = err.message;
                prodError.classList.remove('hidden');
            }
        });
        
        window.editarProducto = function(id, nombre, categoria, peso, stockMin) {
            document.getElementById('modal-producto-title').textContent = 'Editar Producto';
            document.getElementById('prod-id').value = id;
            document.getElementById('prod-nombre').value = nombre;
            document.getElementById('prod-categoria').value = categoria;
            document.getElementById('prod-peso').value = peso;
            document.getElementById('prod-stock-minimo').value = stockMin;
            document.getElementById('prod-error').classList.add('hidden');
            modalProducto.classList.remove('hidden');
        };
        
        window.eliminarProducto = async function(id) {
            if (confirm('¿Estás seguro de eliminar este producto?')) {
                try {
                    await deleteProducto(id);
                    loadProductosList();
                    loadDashboardStats();
                } catch(error) {
                    alert('Error al eliminar: ' + error.message);
                }
            }
        };

        window.setProductFilter = function(filter) {
            activeProductFilter = filter;
            const btnTodos = document.getElementById('filter-prod-todos');
            const btnHistorial = document.getElementById('filter-prod-historial');
            const todosContainer = document.getElementById('productos-todos-container');
            const historialContainer = document.getElementById('productos-historial-container');

            if (filter === 'todos') {
                btnTodos.style.background = 'var(--surface)';
                btnTodos.style.color = 'var(--text-color)';
                btnHistorial.style.background = 'transparent';
                btnHistorial.style.color = 'var(--text-secondary)';
                
                todosContainer.classList.remove('hidden');
                historialContainer.classList.add('hidden');
                loadProductosList();
            } else {
                btnHistorial.style.background = 'var(--surface)';
                btnHistorial.style.color = 'var(--text-color)';
                btnTodos.style.background = 'transparent';
                btnTodos.style.color = 'var(--text-secondary)';
                
                historialContainer.classList.remove('hidden');
                todosContainer.classList.add('hidden');
                loadProductosHistorial();
            }
        };
 
        async function loadProductosList() {
            const tbody = document.getElementById('productos-table');
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 24px;">Cargando...</td></tr>';
            
            const data = await getProductos();
            const searchVal = document.getElementById('productos-search').value.toLowerCase().trim();
            
            const filtered = data.filter(p => p.nombre.toLowerCase().includes(searchVal));
            document.getElementById('prod-total-badge').textContent = filtered.length;
            
            if (!filtered || !filtered.length) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 24px; color: var(--text-secondary);">No hay productos que coincidan.</td></tr>';
                return;
            }
            
            tbody.innerHTML = filtered.map(p => {
                
                const stock = p.stock_minimo || 0;
                
                // Buscar si tiene inventario o celda vinculada
                let celdaText = `<button class="btn btn-outline" style="padding: 6px 12px; font-size: 11px;" onclick="abrirModalVincularCelda('${p.id}')">Vincular Celda</button>`;
                if (p.inventario_actual && p.inventario_actual.length > 0) {
                    const inv = p.inventario_actual[0];
                    celdaText = `<span style="font-size: 12px; color: var(--primary-color); font-weight: 600;"><ion-icon name="scale-outline"></ion-icon> Vinculado</span>`;
                }
                
                const nombreEscaped = p.nombre.replace(/'/g, "\\'");
                const catEscaped = (p.categoria || '').replace(/'/g, "\\'");
                
                return `
                <tr>
                    <td style="font-weight: 600; color: var(--text-secondary);">${p.id}</td>
                    <td style="font-weight: 700; color: var(--text-color);">${p.nombre}</td>
                    <td>${p.peso_unidad} g</td>
                    <td><span class="badge ${stock <= 5 ? 'badge-danger' : 'badge-success'}" style="font-weight: 700; font-size: 13px;">${stock} u</span></td>
                    <td>${celdaText}</td>
                    <td style="text-align: right;">
                        <button onclick="editarProducto(${p.id}, '${nombreEscaped}', '${catEscaped}', ${p.peso_unidad}, ${p.stock_minimo || 0})" class="btn" style="padding: 6px; background: rgba(16, 185, 129, 0.15); color: #10b981; margin-right: 4px;" title="Editar">
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <button onclick="eliminarProducto(${p.id})" class="btn" style="padding: 6px; background: rgba(239, 68, 68, 0.15); color: #ef4444;" title="Eliminar">
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                    </td>
                </tr>
                `;
            }).join('');
        }

        async function loadProductosHistorial() {
            const container = document.getElementById('productos-historial-container');
            container.innerHTML = '<div style="text-align: center; color: var(--text-secondary); padding: 32px;">Cargando historial de cambios...</div>';
            
            const logs = await getProductLogs();
            if (!logs || !logs.length) {
                container.innerHTML = '<div class="card" style="padding: 32px; text-align: center; color: var(--text-secondary);">No hay registros de cambios en el catálogo.</div>';
                return;
            }

            container.innerHTML = logs.map(l => {
                let badgeClass = 'badge-success';
                let badgeText = 'CREACIÓN';
                let iconName = 'add-circle-outline';
                let iconColor = '#10b981';

                if (l.accion === 'MODIFICACION') {
                    badgeClass = 'badge-warning';
                    badgeText = 'EDITADO';
                    iconName = 'create-outline';
                    iconColor = '#f59e0b';
                } else if (l.accion === 'ELIMINACION') {
                    badgeClass = 'badge-danger';
                    badgeText = 'ELIMINADO';
                    iconName = 'trash-outline';
                    iconColor = '#ef4444';
                }

                const userStr = l.usuario_nombre ? `${l.usuario_nombre} (${l.usuario_rol || 'Empleado'})` : 'Sistema';
                const dateStr = new Date(l.fecha_hora).toLocaleString('es-MX', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });

                return `
                <div class="card" style="display: flex; gap: 16px; padding: 16px; border-left: 4px solid ${iconColor};">
                    <div style="background: var(--surface-alt); width: 36px; height: 36px; border-radius: 18px; display: flex; align-items: center; justify-content: center; color: ${iconColor};">
                        <ion-icon name="${iconName}" style="font-size: 20px;"></ion-icon>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px;">
                            <div>
                                <h4 style="font-size: 15px; font-weight: 700; color: var(--text-color);">${l.producto_nombre}</h4>
                                <p style="font-size: 12px; color: var(--text-muted); margin-top: 2px;"><ion-icon name="person-outline"></ion-icon> ${userStr} • <ion-icon name="time-outline"></ion-icon> ${dateStr}</p>
                            </div>
                            <span class="badge ${badgeClass}" style="font-size: 10px; font-weight: 700;">${badgeText}</span>
                        </div>
                        ${l.detalles ? `<p style="font-size: 13px; color: var(--text-secondary); margin-top: 10px; padding: 8px 12px; background: var(--surface-alt); border-radius: 6px;">${l.detalles}</p>` : ''}
                    </div>
                </div>
                `;
            }).join('');
        }

        window.abrirModalVincularCelda = async function(productId) {
            // Reutilizar modal de vinculo desde el catálogo
            document.getElementById('vincular-estante-id').value = '';
            
            // Cargar select con estantes que no tienen productos vinculados
            const estantes = await getEstantes();
            const select = document.getElementById('vincular-producto-id');
            select.innerHTML = '<option value="">Selecciona una Báscula / Estante...</option>' + 
                estantes.map(e => {
                    const isLinked = e.inventario_actual && e.inventario_actual.length > 0;
                    return `<option value="${e.id}" ${isLinked ? 'disabled' : ''}>${e.nombre} (${e.ubicacion_fisica || 'Sin ubicación'}) ${isLinked ? '- Ocupado' : ''}</option>`;
                }).join('');

            // Modificar comportamientos
            document.getElementById('modal-vincular').querySelector('h2').textContent = 'Vincular a Báscula IoT';
            document.getElementById('modal-vincular').querySelector('p').textContent = 'Selecciona la báscula física donde colocarás este producto.';
            document.getElementById('vincular-estante-id').value = productId; 
            
            document.getElementById('modal-vincular').classList.remove('hidden');
        };

        
        document.getElementById('btn-confirmar-vinculo').addEventListener('click', async () => {
            const targetId = document.getElementById('vincular-estante-id').value; // Puede ser estanteId o productId
            const selectVal = document.getElementById('vincular-producto-id').value; // El otro

            if (!selectVal) {
                alert("Por favor realiza una selección válida.");
                return;
            }

            try {
                
                const dataEstantes = await getEstantes();
                const isCatalogFlow = dataEstantes.some(e => e.id === selectVal);
                
                let estanteId = isCatalogFlow ? selectVal : targetId;
                let productoId = isCatalogFlow ? targetId : selectVal;

                await linkProductToShelf(estanteId, productoId);
                closeModal('modal-vincular');
                loadProductosList();
                loadEstantesList();
                loadDashboardStats();
                alert("Vínculo de calibración transmitido con éxito al ESP32.");
            } catch(e) {
                alert("Error al vincular: " + e.message);
            }
        });

        // Dispositivos IoT y acceso de RFID
        
        async function loadEstantesList() {
            const grid = document.getElementById('iot-grid');
            grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: var(--text-muted);">Cargando...</div>';
            
            const data = await getEstantes();
            if (!data || !data.length) {
                grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 24px;">No hay estantes inteligentes registrados.</div>';
                return;
            }

            grid.innerHTML = data.map(e => {
                const isOnline = e.mac_address ? true : false;
                const statusColor = isOnline ? '#10b981' : '#f59e0b';
                const statusText = isOnline ? 'En línea' : 'Desconectado';

                const inventario = e.inventario_actual && e.inventario_actual.length > 0 ? e.inventario_actual[0] : null;
                const producto = inventario ? inventario.productos : null;
                
                const peso = inventario ? (inventario.peso_total_gramos || 0) : null;
                const piezas = inventario ? (inventario.cantidad_calculada || 0) : null;

                let cardHeader = `
                    <div style="display: flex; justify-content: space-between; width: 100%; align-items: flex-start;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="background: rgba(13, 148, 136, 0.15); width: 36px; height: 36px; border-radius: 18px; display: flex; align-items: center; justify-content: center; color: var(--primary-color);">
                                <ion-icon name="server-outline" style="font-size: 18px;"></ion-icon>
                            </div>
                            <div>
                                <h4 style="font-size: 15px; font-weight: 700; color: var(--text-color);">${e.nombre}</h4>
                                <div style="display: flex; align-items: center; gap: 4px; margin-top: 1px;">
                                    <div style="width: 6px; height: 6px; border-radius: 3px; background: ${statusColor};"></div>
                                    <span style="font-size: 11px; color: var(--text-muted);">${statusText} | MAC: ${e.mac_address}</span>
                                </div>
                            </div>
                        </div>
                        <button class="btn" style="padding: 4px; background: transparent; color: #f87171;" onclick="eliminarEstanteIoT(${e.id})">
                            <ion-icon name="trash-outline" style="font-size: 18px;"></ion-icon>
                        </button>
                    </div>
                `;

                let telemetryBox = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; width: 100%; margin-top: 12px; background: var(--surface-alt); padding: 12px; border-radius: 8px;">
                        <div>
                            <span style="font-size: 11px; color: var(--text-muted); display: block;">Peso Celda</span>
                            <span style="font-size: 18px; font-weight: 800; color: var(--text-color);">${peso !== null ? (peso >= 1000 ? (peso/1000).toFixed(2)+' kg' : peso+' g') : '---'}</span>
                        </div>
                        <div>
                            <span style="font-size: 11px; color: var(--text-muted); display: block;">Piezas Físicas</span>
                            <span style="font-size: 18px; font-weight: 800; color: var(--primary-color);">${piezas !== null ? piezas+' u' : 'Sin vincular'}</span>
                        </div>
                    </div>
                `;

                let actionFooter = '';
                if (producto) {
                    actionFooter = `
                        <div style="width: 100%; margin-top: 12px; border-top: 1px solid var(--border-color); padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <ion-icon name="checkmark-circle" style="color: #10b981; font-size: 18px;"></ion-icon>
                                <span style="font-size: 12px; font-weight: 600; color: var(--text-color);">${producto.nombre}</span>
                            </div>
                            <button class="btn btn-secondary" style="padding: 6px 12px; font-size: 11px; color: var(--danger);" onclick="desvincularEstanteIoT(${e.id}, ${producto.id})">Desvincular</button>
                        </div>
                    `;
                } else {
                    actionFooter = `
                        <div style="width: 100%; margin-top: 12px; background: rgba(217, 119, 6, 0.05); border: 1px dashed rgba(217, 119, 6, 0.2); padding: 10px; border-radius: 8px; text-align: center;">
                            <p style="font-size: 11px; color: var(--warning); font-weight: 500; margin-bottom: 8px;">Requiere Vincular Producto</p>
                            <button class="btn btn-primary" style="padding: 6px 12px; font-size: 11px; width: 100%;" onclick="abrirModalVincularEstante(${e.id})">Vincular Producto</button>
                        </div>
                    `;
                }

                return `
                <div class="card" style="display: flex; flex-direction: column; padding: 16px; margin-bottom: 0;">
                    ${cardHeader}
                    <p style="font-size: 12px; color: var(--text-secondary); margin-top: 10px;"><ion-icon name="location-outline"></ion-icon> ${e.ubicacion_fisica || 'Sin ubicación registrada'}</p>
                    ${telemetryBox}
                    ${actionFooter}
                </div>
                `;
            }).join('');
        }

        document.getElementById('form-estante').addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = document.getElementById('estante-nombre').value;
            const mac = document.getElementById('estante-mac').value;
            const ubi = document.getElementById('estante-ubicacion').value;

            try {
                await registerEstante(mac, ubi);
                closeModal('modal-nuevo-estante');
                document.getElementById('form-estante').reset();
                loadEstantesList();
                loadDashboardStats();
                alert("Estante registrado e inicializado.");
            } catch(err) {
                alert("Error registrando estante: " + err.message);
            }
        });

        window.eliminarEstanteIoT = async function(id) {
            if (confirm("¿Estás seguro de eliminar este estante IoT?")) {
                await deleteEstante(id);
                loadEstantesList();
                loadDashboardStats();
            }
        };

        window.abrirModalVincularEstante = async function(estanteId) {
            document.getElementById('vincular-estante-id').value = estanteId;
            
            const prods = await getProductos();
            const select = document.getElementById('vincular-producto-id');
            select.innerHTML = '<option value="">Seleccione un producto...</option>' + 
                prods.map(p => `<option value="${p.id}">${p.nombre} (${p.peso_unidad}g)</option>`).join('');
                
            document.getElementById('modal-vincular').classList.remove('hidden');
        };

        window.desvincularEstanteIoT = async function(estanteId, productId) {
            if (confirm("¿Desvincular el producto de esta báscula?")) {
                await unlinkProductFromShelf(estanteId, productId);
                loadEstantesList();
                loadProductosList();
                loadDashboardStats();
            }
        };

        // Control de acceso con RFID
        
        async function loadProfilesRfid() {
            const container = document.getElementById('rfid-profiles-list');
            container.innerHTML = '<div style="text-align: center; color: var(--text-muted);">Cargando perfiles...</div>';
            
            const { data, error } = await supabase
                .from('perfiles')
                .select('*, roles(nombre)');

            if (error) {
                console.error(error);
                return;
            }

            container.innerHTML = data.map(p => {
                const roleName = p.roles?.nombre || 'Empleado';
                const rfid = p.rfid_tag ? p.rfid_tag : 'Sin vincular';
                const rfidColor = p.rfid_tag ? 'var(--primary-color)' : 'var(--text-muted)';
                const rfidIcon = p.rfid_tag ? 'card' : 'card-outline';

                return `
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--separator);">
                    <div>
                        <h4 style="font-size: 14px; font-weight: 700; color: var(--text-color);">${p.nombre_completo}</h4>
                        <span style="font-size: 11px; color: var(--text-muted);">${roleName}</span>
                    </div>
                    <button class="btn btn-secondary" style="padding: 6px 12px; font-size: 11px; display: flex; align-items: center; gap: 4px; color: ${rfidColor};" onclick="abrirModalVincularRfid('${p.id}', '${p.nombre_completo}')">
                        <ion-icon name="${rfidIcon}"></ion-icon>
                        <span style="font-size: 11px; font-weight: 600;">${p.rfid_tag ? 'Actualizar' : 'Vincular'}</span>
                    </button>
                </div>
                `;
            }).join('');
        }

        window.abrirModalVincularRfid = function(profileId, profileName) {
            selectedProfileRfid = profileId;
            rfidCountdown = 10;
            
            document.getElementById('rfid-modal-title').textContent = 'Vinculando a ' + profileName;
            document.getElementById('rfid-countdown-number').textContent = rfidCountdown;
            document.getElementById('rfid-status-box').style.background = 'var(--surface-alt)';
            document.getElementById('rfid-status-box').querySelector('p').textContent = 'Esperando escaneo físico...';
            
            document.getElementById('modal-vincular-rfid').classList.remove('hidden');

            if (rfidTimer) clearInterval(rfidTimer);
            
            rfidTimer = setInterval(() => {
                rfidCountdown--;
                document.getElementById('rfid-countdown-number').textContent = rfidCountdown;
                
                if (rfidCountdown <= 0) {
                    clearInterval(rfidTimer);
                    document.getElementById('rfid-status-box').style.background = 'var(--danger-bg)';
                    document.getElementById('rfid-status-box').querySelector('p').textContent = 'Tiempo límite excedido.';
                    document.getElementById('rfid-countdown-number').textContent = '✕';
                }
            }, 1000);
        };

        window.cancelarVinculoRfid = function() {
            if (rfidTimer) clearInterval(rfidTimer);
            selectedProfileRfid = null;
            closeModal('modal-vincular-rfid');
        };

        window.autoAssignRfid = async function(rfidTag, profileId) {
            try {
                if (rfidTimer) clearInterval(rfidTimer);
                
                const { error } = await supabase
                    .from('perfiles')
                    .update({ rfid_tag: rfidTag })
                    .eq('id', profileId);

                if (error) throw error;

                document.getElementById('rfid-status-box').style.background = 'var(--success-bg)';
                document.getElementById('rfid-status-box').querySelector('p').textContent = 'Tarjeta vinculada con éxito!';
                document.getElementById('rfid-countdown-number').textContent = '✓';
                document.getElementById('rfid-countdown-number').style.color = 'var(--success)';
                
                setTimeout(() => {
                    cancelarVinculoRfid();
                    loadProfilesRfid();
                }, 1500);

            } catch (err) {
                alert("Error vinculando tarjeta: " + err.message);
                cancelarVinculoRfid();
            }
        };

        // Historial de Movimiento
        
        window.abrirModalNuevoMovimiento = async function() {
            const errDiv = document.getElementById('movimiento-error');
            errDiv.classList.add('hidden');
            document.getElementById('form-movimiento').reset();
            document.getElementById('mov-resumen-peso').classList.add('hidden');
            document.getElementById('mov-stock-alerta').classList.add('hidden');
            
            // Cargar productos
            const prods = await getProductos();
            const prodSelect = document.getElementById('mov-producto-id');
            prodSelect.innerHTML = '<option value="">Seleccione un producto...</option>' + 
                prods.map(p => `<option value="${p.id}" data-peso="${p.peso_unidad}">${p.nombre} (${p.peso_unidad} g)</option>`).join('');

            // Cargar estantes
            const estantes = await getEstantes();
            const estSelect = document.getElementById('mov-estante-id');
            estSelect.innerHTML = '<option value="">Ninguno (Ajuste general de stock)</option>' + 
                estantes.map(e => `<option value="${e.id}">${e.nombre} (${e.ubicacion_fisica || 'Sin MAC'})</option>`).join('');

            // Tipo de movimiento
            window.setMovimientoTipo('ENTRADA');
            
            document.getElementById('modal-nuevo-movimiento').classList.remove('hidden');
        };

        window.setMovimientoTipo = function(tipo) {
            activeMovimientoTipo = tipo;
            const btnEntrada = document.getElementById('btn-mov-entrada');
            const btnSalida = document.getElementById('btn-mov-salida');

            if (tipo === 'ENTRADA') {
                btnEntrada.className = 'btn btn-success';
                btnSalida.className = 'btn btn-secondary';
            } else {
                btnEntrada.className = 'btn btn-secondary';
                btnSalida.className = 'btn btn-danger';
            }
            
            window.actualizarMovimientoResumen();
        };

        window.actualizarMovimientoResumen = async function() {
            const prodSelect = document.getElementById('mov-producto-id');
            const cantInput = document.getElementById('mov-cantidad');
            const resumenDiv = document.getElementById('mov-resumen-peso');
            const stockAlerta = document.getElementById('mov-stock-alerta');
            
            const selectedOpt = prodSelect.options[prodSelect.selectedIndex];
            const cantidad = parseInt(cantInput.value) || 0;
            
            if (!selectedOpt || !selectedOpt.value || cantidad <= 0) {
                resumenDiv.classList.add('hidden');
                stockAlerta.classList.add('hidden');
                return;
            }

            const pesoUnit = parseFloat(selectedOpt.getAttribute('data-peso')) || 0;
            const pesoTotalG = cantidad * pesoUnit;
            const pesoText = pesoTotalG >= 1000 ? (pesoTotalG / 1000).toFixed(2) + ' kg' : pesoTotalG + ' g';
            
            document.getElementById('mov-resumen-peso-valor').textContent = pesoText;
            resumenDiv.classList.remove('hidden');

            // Validar stock para salidas
            if (activeMovimientoTipo === 'SALIDA') {
                const stock = await getStockActual(selectedOpt.value);
                document.getElementById('mov-stock-alerta-texto').textContent = `Stock disponible: ${stock} unidades.`;
                stockAlerta.classList.remove('hidden');
                
                if (cantidad > stock) {
                    stockAlerta.style.background = 'var(--danger-bg)';
                    stockAlerta.style.color = 'var(--danger)';
                } else {
                    stockAlerta.style.background = 'var(--primary-light)';
                    stockAlerta.style.color = 'var(--primary-color)';
                }
            } else {
                stockAlerta.classList.add('hidden');
            }
        };

        document.getElementById('form-movimiento').addEventListener('submit', async (e) => {
            e.preventDefault();
            const errDiv = document.getElementById('movimiento-error');
            const btnSubmit = document.getElementById('btn-movimiento-submit');
            
            const prodSelect = document.getElementById('mov-producto-id');
            const estanteId = document.getElementById('mov-estante-id').value;
            const cantidad = parseInt(document.getElementById('mov-cantidad').value);

            const selectedOpt = prodSelect.options[prodSelect.selectedIndex];
            const pesoUnit = parseFloat(selectedOpt.getAttribute('data-peso')) || 0;

            errDiv.classList.add('hidden');
            btnSubmit.innerHTML = 'Procesando...';
            btnSubmit.disabled = true;

            const payload = {
                producto_id: selectedOpt.value,
                estante_id: estanteId || null,
                tipo_accion: activeMovimientoTipo,
                cantidad: cantidad,
                peso_individual_gramos: pesoUnit
            };

            try {
                await createMovimiento(payload);
                closeModal('modal-nuevo-movimiento');
                loadHistorialList();
                loadDashboardStats();
            } catch (err) {
                errDiv.textContent = err.message;
                errDiv.classList.remove('hidden');
            } finally {
                btnSubmit.innerHTML = 'Registrar';
                btnSubmit.disabled = false;
            }
        });

        window.eliminarMovimiento = async function(id) {
            if (confirm("¿Estás seguro de eliminar este movimiento del historial?")) {
                await deleteMovimientoService(id);
                loadHistorialList();
                loadDashboardStats();
            }
        };

        async function loadHistorialList() {
            const listContainer = document.getElementById('movimientos-list-container');
            listContainer.innerHTML = '<div style="text-align: center; padding: 24px; color: var(--text-muted);">Cargando...</div>';
            
            const searchVal = document.getElementById('movimientos-search').value.toLowerCase().trim();
            const filterTipo = document.getElementById('movimientos-filter-tipo').value;

            const allMovs = await getMovimientos(60);
            const stats = await getMovimientosStatistics();

            // Actualizar badges de stats
            document.getElementById('stats-mov-total').textContent = stats.total;
            document.getElementById('stats-mov-entradas').textContent = stats.entradas;
            document.getElementById('stats-mov-salidas').textContent = stats.salidas;

            // Filtrar en frontend
            const filtered = allMovs.filter(m => {
                const matchesSearch = (m.productos?.nombre || '').toLowerCase().includes(searchVal) || 
                                      (m.estantes?.nombre || '').toLowerCase().includes(searchVal);
                const matchesTipo = filterTipo === 'TODOS' || m.tipo_accion === filterTipo;
                return matchesSearch && matchesTipo;
            });

            if (!filtered || !filtered.length) {
                listContainer.innerHTML = '<div class="card" style="padding: 24px; text-align: center; color: var(--text-secondary);">No se encontraron movimientos registrados.</div>';
                return;
            }

            // Validar rol de usuario actual
            let isAdminUser = false;
            if (currentUser) {
                const { data } = await supabase.from('perfiles').select('role_id').eq('id', currentUser.id).single();
                if (data && (data.role_id === 1 || data.role_id === 2)) {
                    isAdminUser = true;
                }
            }

            listContainer.innerHTML = filtered.map(m => {
                const isEntrada = m.tipo_accion === 'ENTRADA';
                const sideColor = isEntrada ? '#10b981' : '#ef4444';
                const badgeBg = isEntrada ? 'rgba(16, 185, 129, 0.15)' : 'rgba(239, 68, 68, 0.15)';
                const badgeText = isEntrada ? `+${m.cantidad} u` : `-${m.cantidad} u`;
                
                const pesoAbs = Math.abs(m.diferencia_peso_gramos);
                const pesoText = pesoAbs >= 1000 ? (pesoAbs/1000).toFixed(2) + ' kg' : pesoAbs + ' g';
                const dateStr = new Date(m.fecha_hora).toLocaleString('es-MX', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' });

                return `
                <div class="card" style="border-left: 5px solid ${sideColor}; display: flex; align-items: center; justify-content: space-between; padding: 16px; margin-bottom: 0; flex-wrap: wrap; gap: 16px;">
                    <div style="flex: 1; min-width: 250px;">
                        <h4 style="font-size: 15px; font-weight: 700; color: var(--text-color);">${m.productos?.nombre || 'Producto Desconocido'}</h4>
                        <p style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">
                            📍 Celda: ${m.estantes?.nombre || 'General / Ajuste'} • <ion-icon name="time-outline"></ion-icon> ${dateStr}
                        </p>
                        <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">
                            Por: ${m.usuario_nombre || 'Sistema'} (${m.usuario_rol || 'Operador'})
                        </p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="text-align: right;">
                            <span class="badge" style="background: ${badgeBg}; color: ${sideColor}; font-weight: 800; font-size: 14px; padding: 6px 12px;">${badgeText}</span>
                            <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 4px;">⚖️ ${pesoText}</span>
                        </div>
                        ${isAdminUser ? `
                            <button class="btn" style="padding: 6px; background: transparent; color: #f87171;" onclick="eliminarMovimiento('${m.id}')" title="Eliminar del historial">
                                <ion-icon name="trash-outline" style="font-size: 18px;"></ion-icon>
                            </button>
                        ` : ''}
                    </div>
                </div>
                `;
            }).join('');
        }

        // Perfil de usuario
        
        async function loadPerfilData() {
            if (!currentUser) return;
            
            try {
                // Obtenner datos del perfil
                const { data, error } = await supabase
                    .from('perfiles')
                    .select('*, roles(nombre)')
                    .eq('id', currentUser.id)
                    .single();
                
                if (error) throw error;

                document.getElementById('perfil-nombre-completo').textContent = data.nombre_completo || currentUser.user_metadata?.full_name || 'Usuario de Vantage';
                document.getElementById('perfil-username-tag').textContent = `@${data.username || currentUser.user_metadata?.username || 'sin_usuario'}`;
                document.getElementById('perfil-rol-tag').textContent = `Rol: ${data.roles?.nombre || 'Empleado'}`;
                document.getElementById('perfil-email-tag').innerHTML = `<ion-icon name="mail-outline"></ion-icon> ${currentUser.email}`;
                
                document.getElementById('perfil-avatar-circle').textContent = (data.nombre_completo || 'U').charAt(0).toUpperCase();

            } catch (err) {
                console.error("Error cargando perfil:", err);
            }
        }

        window.abrirModalEditarPerfil = async function() {
            const { data } = await supabase.from('perfiles').select('nombre_completo').eq('id', currentUser.id).single();
            document.getElementById('perfil-edit-nombre').value = data?.nombre_completo || '';
            document.getElementById('modal-editar-perfil').classList.remove('hidden');
        };

        document.getElementById('form-editar-perfil').addEventListener('submit', async (e) => {
            e.preventDefault();
            const nuevoNombre = document.getElementById('perfil-edit-nombre').value;
            
            try {
                const { error } = await supabase
                    .from('perfiles')
                    .update({ nombre_completo: nuevoNombre })
                    .eq('id', currentUser.id);

                if (error) throw error;
                
                closeModal('modal-editar-perfil');
                loadPerfilData();
                alert("Nombre de perfil actualizado correctamente.");
            } catch (err) {
                alert("Error al actualizar: " + err.message);
            }
        });

        
        
        function setupRealtimeSubscriptions() {
            supabase.removeAllChannels(); 
            // Movimientos en tiempo real
            supabase
              .channel('realtime-movs')
              .on('postgres_changes', { event: '*', schema: 'public', table: 'historial_movimientos' }, () => {
                 console.log("Realtime: Movimiento detectado!");
                 const activeTab = document.querySelector('.nav-item.active')?.getAttribute('data-target');
                 if (activeTab === 'dashboard-view') {
                     loadDashboardStats();
                     loadDashboardMovimientos();
                 } else if (activeTab === 'historial-view') {
                     loadHistorialList();
                 }
              })
              .subscribe();

            // Peso en tiempo real
            supabase
              .channel('realtime-scales')
              .on('postgres_changes', { event: '*', schema: 'public', table: 'inventario_actual' }, () => {
                 console.log("Realtime: Telemetría de báscula actualizada!");
                 const activeTab = document.querySelector('.nav-item.active')?.getAttribute('data-target');
                 if (activeTab === 'iot-view') {
                     loadEstantesList();
                 } else if (activeTab === 'productos-view' && activeProductFilter === 'todos') {
                     loadProductosList();
                 }
              })
              .subscribe();

            // Estantes en tiempo real
            supabase
              .channel('realtime-rfid-scans')
              .on('postgres_changes', { event: 'UPDATE', schema: 'public', table: 'estantes' }, (payload) => {
                 const isModalOpen = !document.getElementById('modal-vincular-rfid').classList.contains('hidden');
                 const rfidEscaneado = payload.new?.ultimo_rfid_escaneado;
                 
                 if (isModalOpen && rfidEscaneado && selectedProfileRfid) {
                     console.log("💳 RFID físico detectado de estante en tiempo real:", rfidEscaneado);
                     window.autoAssignRfid(rfidEscaneado, selectedProfileRfid);
                 }
                 
                 const activeTab = document.querySelector('.nav-item.active')?.getAttribute('data-target');
                 if (activeTab === 'iot-view') {
                     loadEstantesList();
                 }
              })
              .subscribe();
        }

        // Inicialización
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    </script>
</body>
</html>
