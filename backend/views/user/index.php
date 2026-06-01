<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Botón para mostrar/ocultar búsqueda -->
    <p>
        <a class="btn btn-secondary" data-bs-toggle="collapse" href="#searchBox" role="button">
            Mostrar / Ocultar Búsqueda
        </a>
    </p>

    <!-- Collapse Bootstrap 5 -->
    <div class="collapse" id="searchBox">
        <div class="card card-body">
            <?= $this->render('_search', ['model' => $searchModel]) ?>
        </div>
    </div>

    <div class="mt-4">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                ['attribute' => 'userIdLink', 'format' => 'raw'],
                ['attribute' => 'userLink', 'format' => 'raw'],
                ['attribute' => 'perfilLink', 'format' => 'raw'],

                'email:email',

                [
                    'attribute' => 'rol_id',
                    'label' => 'Rol',
                    'value' => function ($model) {
                        return $model->rol ? $model->rol->rol_nombre : '- sin rol -';
                    },
                ],

                [
                    'attribute' => 'estado_id',
                    'label' => 'Estado',
                    'value' => function ($model) {
                        return $model->estado ? $model->estado->estado_nombre : '- sin estado -';
                    },
                ],

                'created_at:datetime',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>

</div>