<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\District */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="district-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'date_pay')->textInput(['style' => 'display:none']) ?>
    <p><?= $model['date_pay']?></p>

    <div class="form-group">
        <?= Html::submitButton('Сформировать долг', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
