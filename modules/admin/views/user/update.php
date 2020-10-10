<?php

use app\modules\user\models\forms\UserUpdateForm;

/* @var $this yii\web\View */
/* @var $model UserUpdateForm */
/* @var $id int */

$this->title = Yii::t('app', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">

    <?= $this->render('_form_update', [
        'model' => $model,
    ]) ?>

</div>
