<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ApiLogger */

$this->title = 'Update Api Logger: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Api Loggers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="api-logger-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
