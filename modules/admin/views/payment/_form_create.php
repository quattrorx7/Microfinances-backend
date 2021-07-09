<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\District */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="district-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date_pay')
        ->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '9999-99-99',
        ])
    ?>

    <div class="form-group">
        <?= Html::submitButton('Создать платеж', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
