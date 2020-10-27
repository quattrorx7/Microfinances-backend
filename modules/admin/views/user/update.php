<?php

use app\modules\user\forms\UserUpdateForm;

/* @var $this yii\web\View */
/* @var $model UserUpdateForm */
/* @var $id int */

$this->title = 'Редактировать';
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">

    <?= $this->render('_form_update', [
        'model' => $model,
    ]) ?>

</div>
