<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PermisosHelpers;

/**
 * @var yii\web\View $this
 * @var frontend\models\Perfil $model
 */

/* 1. Título dinámico con el nombre de usuario */
$this->title = $model->user ? $model->user->username : "Perfil #" . $model->id;

/* 2. Verificación de permisos para SuperUsuario */
$mostrar_esta_nav = PermisosHelpers::requerirMinimoRol('SuperUsuario');

$this->params['breadcrumbs'][] = ['label' => 'Perfiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-view">

    <h1>Perfil: <?= Html::encode($this->title) ?></h1>

    <p>
        <?php 
        /* 3. Botones protegidos: solo visibles para SuperUsuarios logueados */
        if (!Yii::$app->user->isGuest && $mostrar_esta_nav) {
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        } 
        ?>

        <?php 
        if (!Yii::$app->user->isGuest && $mostrar_esta_nav) {
            echo ' ' . Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        } 
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            /* 4. Enlace al Usuario (usando el método getUserLink del modelo) */
            [
                'attribute' => 'userLink',
                'format' => 'raw',
            ],
            'nombre',
            'apellido',
            'fecha_nacimiento',
            /* 5. Muestra el nombre del género a través de la relación */
            [
                'attribute' => 'genero.genero_nombre',
                'label' => 'Género',
            ],
            'created_at',
            'updated_at',
            /* 6. Enlace en el ID (usando el método getPerfilIdLink del modelo) */
            [
                'attribute' => 'perfilIdLink',
                'format' => 'raw',
                'label' => 'Perfil ID',
            ],
        ],
    ]) ?>

</div>