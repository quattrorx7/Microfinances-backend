<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ApiLogger */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Api Loggers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-logger-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created_at',
            'duration',
            'url:url',
            'method',
            'request',
            'headers',
            'answer',
            'app_version',
            'app_platform',
            'user_id',
        ],
    ]) ?>

</div>
