<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker; // Asegúrate de que esta línea esté aquí

/* @var $this yii\web\View */
/* @var $model frontend\models\Perfil */
/* @var $form yii\widgets\ActiveForm */
?>


    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => 45]) ?>

    <?php // REEMPLAZO DEL TEXTINPUT POR EL WIDGET DEL LIBRO ?>
    <?= $form->field($model, 'fecha_nacimiento')->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
        'clientOptions' => [
            'dateFormat' => 'yy-mm-dd', // Formato que pide el libro
            'changeMonth' => true,
            'changeYear' => true,
            'yearRange' => '-100:+0',
        ],
        'options' => ['class' => 'form-control'] // Esto es para que no pierda el diseño de Bootstrap
    ]) ?>

    <?= $form->field($model, 'genero_id')->dropDownList($model->generoLista, ['prompt' => 'Por favor Seleccione Uno' ]);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>