<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PermisosHelpers;

/* @var $this yii\web\View */
/* @var $model common\models\user */

$this->title = $model->username;
$show_this_nav = PermisosHelpers::requerirMinimoRol('SuperUsuario');

$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest && $show_this_nav): ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                // Cambiado de profileLink a perfilLink para coincidir con tu modelo
                'attribute' => 'perfilLink', 
                'format' => 'raw',
                'value' => $model->getPerfilLink(), 
            ],
            
            'email:email',

            [
                'attribute' => 'rolNombre',
                'label' => 'Rol',
                // Llamamos directamente al método que creamos en el modelo
                'value' => $model->getRolNombre(),
            ],

            [
                'attribute' => 'estadoNombre',
                'label' => 'Estado',
                'value' => $model->getEstadoNombre(),
            ],

            [
                'attribute' => 'tipoUsuarioNombre',
                'label' => 'Tipo Usuario',
                'value' => $model->getTipoUsuarioNombre(),
            ],

            'created_at:datetime',
            'updated_at:datetime',
            'id',
        ],
    ]) ?>

</div>