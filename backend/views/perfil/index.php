<?php
 
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
 
$this->title = 'Perfiles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-index">
 
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
    <a class="btn btn-secondary" data-bs-toggle="collapse" href="#searchBox" role="button">
        Mostrar / Ocultar Búsqueda
    </a>
    </p>

<div class="collapse" id="searchBox">
    <div class="card card-body">
        <?= $this->render('_search', ['model' => $searchModel]) ?>
    </div>
</div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            ['attribute'=>'perfilIdLink', 'format'=>'raw'],
            ['attribute'=>'userLink', 'format'=>'raw'],
            'nombre',
            'apellido',
            'fecha_nacimiento',
            'generoNombre',
            
            // ✅ ActionColumn corregida
            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return \yii\helpers\Url::to([$action, 'id' => $model->id]);
                },
            ],
            
            // 'created_at',
            // 'updated_at',
            // 'user_id',
        ],
    ]); ?>

</div>