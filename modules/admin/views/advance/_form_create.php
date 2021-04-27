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

    <?= $form->field($model, 'issue_date')
        ->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99',
        ])
       ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'limitation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->dropDownList(ArrayHelper::map($users, 'id', 'fullname'), ['maxlength' => true]) ?>

    <?= $form->field($model, 'client_id')->dropDownList(ArrayHelper::map($clients, 'id', 'name'), ['maxlength' => true]) ?>

    <?= $form->field($model, 'daily_payment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model2, 'files[]')->fileInput(['multiple' => true]) ?>

    <?= $form->field($model3, 'note')->fileInput() ?>



    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
