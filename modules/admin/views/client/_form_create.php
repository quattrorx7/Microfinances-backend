<?php

use app\modules\user\forms\UserCreateForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model UserCreateForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'additional_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'residence_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'work_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'district_id')->dropDownList(ArrayHelper::map($districts, 'id', 'title'), ['maxlength' => true]) ?>

    <?= $form->field($model, 'files[]')->fileInput(['multiple' => true]) ?>

    <?= $form->field($model, 'activity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'profit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model2, 'user_id')->dropDownList(ArrayHelper::map($users, 'id', 'fullname'), ['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
