<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ApiLoggerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Api Loggers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-logger-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Api Logger', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'created_at',
            'duration',
            'url:url',
            'method',
            // 'request',
            // 'headers',
            // 'answer',
            // 'app_version',
            // 'app_platform',
            // 'user_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
