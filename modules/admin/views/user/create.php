<?php

use app\modules\user\models\forms\UserCreateForm;

/* @var $this yii\web\View */
/* @var $model UserCreateForm */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app',  'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <div class="box box-primary">

        <div class="box-body">
            <?= $this->render('_form_create', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
