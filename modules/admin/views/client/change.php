<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\District */

$this->title = 'Изменить сотрудника у клиента';
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-create">

    <?= $this->render('_form_change', [
        'model' => $model,
        'users' => $users,
    ]) ?>

</div>
