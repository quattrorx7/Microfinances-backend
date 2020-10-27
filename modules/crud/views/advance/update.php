<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Advance */

$this->title = 'Update Advance: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Advances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="advance-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
