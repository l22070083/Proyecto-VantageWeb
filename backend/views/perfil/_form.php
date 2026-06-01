<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\Perfil $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="perfil-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_nacimiento')->textInput() ?>

    <?= $form->field($model, 'genero_id')->dropDownList($model->generoLista, [
        'prompt' => 'Seleccione Género'
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
    <?php $form = ActiveForm::begin(); ?>

<?= $form->errorSummary($model); // Esto te dirá exactamente por qué no guarda ?>


</div>