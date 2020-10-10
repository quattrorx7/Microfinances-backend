<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ApiLogger */

$this->title = 'Create Api Logger';
$this->params['breadcrumbs'][] = ['label' => 'Api Loggers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-logger-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
