<?php
/* @var $this yii\web\View */
?>

<!-- Modal De Estante Nuevo -->
<div id="modal-nuevo-estante" class="modal-backdrop hidden">
    <div class="modal-content">
        <h2 style="font-size: 20px; font-weight: 600; margin-bottom: 16px;">Registrar Estante Inteligente</h2>
        <form id="form-estante">
            <div class="form-group mb-4">
                <label>Nombre del Estante</label>
                <input type="text" id="estante-nombre" required class="form-control" placeholder="Ej: Estante A1">
            </div>
            <div class="form-group mb-4">
                <label>Dirección MAC (ESP32)</label>
                <input type="text" id="estante-mac" required class="form-control" placeholder="Ej: AA:BB:CC:DD:EE:FF">
            </div>
            <div class="form-group mb-4">
                <label>Ubicación Física</label>
                <input type="text" id="estante-ubicacion" required class="form-control" placeholder="Ej: Pasillo 1, Fila A">
            </div>
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeModal('modal-nuevo-estante')">Cancelar</button>
                <button type="submit" class="btn btn-primary" style="flex: 1;">Registrar</button>
            </div>
        </form>
    </div>
</div>

<!-- Vinculación de Producto a estante -->
<div id="modal-vincular" class="modal-backdrop hidden">
    <div class="modal-content">
        <h2 style="font-size: 20px; font-weight: 600; margin-bottom: 16px;">Vincular Producto</h2>
        <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 16px;">Selecciona el producto que se colocará en este estante.</p>
        <input type="hidden" id="vincular-estante-id">
        <div class="form-group mb-4">
            <label>Producto a vincular</label>
            <select id="vincular-producto-id" required class="form-control">
                <option value="">Seleccione un producto...</option>
            </select>
        </div>
        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeModal('modal-vincular')">Cancelar</button>
            <button type="button" id="btn-confirmar-vinculo" class="btn btn-primary" style="flex: 1;">Confirmar Vínculo</button>
        </div>
    </div>
</div>

<!-- Modal de Producto -->
<div id="modal-producto" class="modal-backdrop hidden">
    <div class="modal-content">
        <h3 id="modal-producto-title" style="font-size: 20px; font-weight: 600; margin-bottom: 16px;">Nuevo Producto</h3>
        <form id="form-producto">
            <input type="hidden" id="prod-id">
            <div class="form-group mb-3">
                <label>Nombre del Producto *</label>
                <input type="text" id="prod-nombre" class="form-control" required placeholder="Ej: Tornillo de Acero">
            </div>
            <div class="form-group mb-3">
                <label>Categoría</label>
                <input type="text" id="prod-categoria" class="form-control" placeholder="Ej: Herramientas">
            </div>
            <div class="form-group mb-3">
                <label>Peso Unitario (Gramos) *</label>
                <input type="number" id="prod-peso" class="form-control" step="1" required placeholder="Ej: 15">
            </div>
            <div class="form-group mb-4">
                <label>Stock Mínimo Alerta *</label>
                <input type="number" id="prod-stock-minimo" class="form-control" required placeholder="Ej: 5">
            </div>
            <div id="prod-error" class="hidden" style="background: var(--danger-bg); color: var(--danger); padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 13px; text-align: center;"></div>
            <div style="display: flex; gap: 12px;">
                <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeModal('modal-producto')">Cancelar</button>
                <button type="submit" class="btn btn-primary" style="flex: 1;">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Edición de perfil -->
<div id="modal-editar-perfil" class="modal-backdrop hidden">
    <div class="modal-content">
        <h2 style="font-size: 20px; font-weight: 600; margin-bottom: 16px;">Editar Perfil</h2>
        <form id="form-editar-perfil">
            <div class="form-group mb-4">
                <label>Nombre Completo</label>
                <input type="text" id="perfil-edit-nombre" required class="form-control" placeholder="Ej: Tommy Alcocer">
            </div>
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeModal('modal-editar-perfil')">Cancelar</button>
                <button type="submit" class="btn btn-primary" style="flex: 1;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Agregar movimiento  -->
<div id="modal-nuevo-movimiento" class="modal-backdrop hidden">
    <div class="modal-content" style="max-width: 420px;">
        <h2 style="font-size: 20px; font-weight: 600; margin-bottom: 16px;">Registrar Movimiento Manual</h2>
        
        <div id="movimiento-error" class="hidden" style="background: var(--danger-bg); color: var(--danger); padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 13px; text-align: center;"></div>
        
        <form id="form-movimiento">
            
            <div class="form-group mb-4">
                <label>Tipo de Acción</label>
                <div style="display: flex; gap: 12px;">
                    <button type="button" id="btn-mov-entrada" class="btn btn-success" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 12px;" onclick="setMovimientoTipo('ENTRADA')">
                        <ion-icon name="arrow-up-right-outline" style="font-size: 16px;"></ion-icon> Entrada
                    </button>
                    <button type="button" id="btn-mov-salida" class="btn btn-secondary" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 12px;" onclick="setMovimientoTipo('SALIDA')">
                        <ion-icon name="arrow-down-left-outline" style="font-size: 16px;"></ion-icon> Salida
                    </button>
                </div>
            </div>

            <!-- Selección de Producto -->
            <div class="form-group mb-3">
                <label>Producto</label>
                <select id="mov-producto-id" required class="form-control" onchange="actualizarMovimientoResumen()">
                    <option value="">Seleccione un producto...</option>
                </select>
            </div>

            <!-- Seleccionar Estante -->
            <div class="form-group mb-3">
                <label>Estante / Celda Asociada (Opcional)</label>
                <select id="mov-estante-id" class="form-control">
                    <option value="">Ninguno (Ajuste general de stock)</option>
                </select>
            </div>

            <!-- Disponibilidad de Stock -->
            <div id="mov-stock-alerta" class="hidden" style="background: var(--primary-light); color: var(--primary-color); padding: 10px; border-radius: 6px; margin-bottom: 16px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 6px;">
                <ion-icon name="information-circle-outline" style="font-size: 18px;"></ion-icon>
                <span id="mov-stock-alerta-texto">Stock disponible: ...</span>
            </div>

            <!-- Cantidad -->
            <div class="form-group mb-3">
                <label>Cantidad (Unidades)</label>
                <input type="number" id="mov-cantidad" required class="form-control" min="1" placeholder="Ej: 5" oninput="actualizarMovimientoResumen()">
            </div>

            <!-- Peso -->
            <div id="mov-resumen-peso" class="hidden" style="background: var(--surface-alt); border: 1px solid var(--border-color); padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 13px; color: var(--text-color);">
                <strong>Peso total estimado:</strong> <span id="mov-resumen-peso-valor">0.00 kg</span>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeModal('modal-nuevo-movimiento')">Cancelar</button>
                <button type="submit" id="btn-movimiento-submit" class="btn btn-primary" style="flex: 1;">Registrar</button>
            </div>
        </form>
    </div>
</div>

<!-- Vincular Tarjeta RFID -->
<div id="modal-vincular-rfid" class="modal-backdrop hidden">
    <div class="modal-content" style="max-width: 380px; text-align: center; padding: 32px 24px;">
        <div style="background: var(--primary-light); width: 70px; height: 70px; border-radius: 35px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <ion-icon name="radio-outline" style="font-size: 36px; color: var(--primary-color); animation: pulse 1.5s infinite;"></ion-icon>
        </div>
        <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 8px;" id="rfid-modal-title">Escaneando Tarjeta RFID</h2>
        <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 24px;" id="rfid-modal-desc">Por favor, aproxima tu tarjeta física a cualquier estante inteligente activo.</p>
        
        <!-- Indicador de Vinculación -->
        <div id="rfid-status-box" style="margin-bottom: 24px; padding: 12px; background: var(--surface-alt); border-radius: 8px; border: 1px solid var(--border-color);">
            <div style="font-size: 32px; font-weight: 800; color: var(--primary-color);" id="rfid-countdown-number">10</div>
            <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Esperando escaneo en hardware...</p>
        </div>
        
        <div style="display: flex; gap: 12px;">
            <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="cancelarVinculoRfid()">Cancelar</button>
        </div>
    </div>
</div>