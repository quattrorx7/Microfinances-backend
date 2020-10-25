<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\District */

$this->title = 'Update District: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Districts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="district-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
