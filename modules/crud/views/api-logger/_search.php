<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\ApiLoggerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="api-logger-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'duration') ?>

    <?= $form->field($model, 'url') ?>

    <?= $form->field($model, 'method') ?>

    <?php // echo $form->field($model, 'request') ?>

    <?php // echo $form->field($model, 'headers') ?>

    <?php // echo $form->field($model, 'answer') ?>

    <?php // echo $form->field($model, 'app_version') ?>

    <?php // echo $form->field($model, 'app_platform') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
