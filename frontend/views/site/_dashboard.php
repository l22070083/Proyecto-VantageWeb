<div id="dashboard-view" class="view-section">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px;">
        <div>
            <p style="font-size: 15px; color: var(--text-muted); margin-bottom: 2px;">Buenos días,</p>
            <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 12px; color: var(--text-color);">Almacén Central</h2>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.05); padding: 5px 12px; border-radius: 6px;">
                <ion-icon name="calendar-outline" style="color: var(--text-muted);"></ion-icon>
                <span id="current-date" style="font-size: 13px; color: var(--text-muted);">Cargando fecha...</span>
            </div>
        </div>
    </div>

    <div style="height: 1px; background: var(--border-color); margin-bottom: 32px;"></div>

    <h3 style="font-size: 18px; margin-bottom: 16px;">Resumen del día</h3>
    <div style="display: flex; gap: 12px; margin-bottom: 24px;">
        <div class="stat-chip" style="background-color: var(--primary-light);">
            <ion-icon name="cube" style="font-size: 20px; color: var(--primary-color); margin-bottom: 4px;"></ion-icon>
            <p id="stat-productos" class="value" style="color: var(--primary-color);">0</p>
            <p class="label" style="color: var(--primary-color);">Productos</p>
        </div>
        <div class="stat-chip" style="background-color: var(--warning-bg);">
            <ion-icon name="warning" style="font-size: 20px; color: var(--warning); margin-bottom: 4px;"></ion-icon>
            <p id="stat-alertas" class="value" style="color: var(--warning);">0</p>
            <p class="label" style="color: var(--warning);">Bajo stock</p>
        </div>
        <div class="stat-chip" style="background-color: var(--success-bg);">
            <ion-icon name="arrow-forward-circle" style="font-size: 20px; color: var(--success); margin-bottom: 4px;"></ion-icon>
            <p id="stat-movimientos" class="value" style="color: var(--success);">0</p>
            <p class="label" style="color: var(--success);">Movimientos hoy</p>
        </div>
    </div>
    
    <h3 style="font-size: 18px; margin-bottom: 16px;">Acciones rápidas</h3>
    <div style="display: flex; gap: 12px; margin-bottom: 32px;">
        <div class="action-card" style="background-color: var(--primary-light);" onclick="window.navigateTo('productos'); document.getElementById('btn-nuevo-producto').click()">
            <div class="action-icon-box" style="background-color: rgba(13, 148, 136, 0.2); color: var(--primary-color);"><ion-icon name="add"></ion-icon></div>
            <span class="action-label" style="color: var(--text-color);">Nuevo producto</span>
        </div>
        <div class="action-card" style="background-color: var(--surface-alt);" onclick="window.navigateTo('productos')">
            <div class="action-icon-box" style="background-color: rgba(148, 163, 184, 0.2); color: var(--text-secondary);"><ion-icon name="list"></ion-icon></div>
            <span class="action-label" style="color: var(--text-color);">Ver todos los productos</span>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="font-size: 18px;">Movimientos recientes</h3>
        <a href="#" style="color: var(--primary-color); text-decoration: none; font-size: 14px; font-weight: 500;" onclick="window.navigateTo('historial')">Ver todo</a>
    </div>
    <div class="card no-padding">
        <table style="width: 100%;">
            <tbody id="dashboard-recent-activity">
                <tr><td style="padding: 24px; text-align: center; color: var(--text-muted);">Cargando actividades...</td></tr>
            </tbody>
        </table>
    </div>
</div>