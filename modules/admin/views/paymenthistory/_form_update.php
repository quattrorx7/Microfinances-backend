<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\District */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="district-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'debt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList($types, ['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
