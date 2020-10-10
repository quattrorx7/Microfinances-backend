<?php

use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use app\modules\apiLogger\models\search\DbLoggerModelSearch;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel DbLoggerModelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $methodFilter array */
/* @var $platformFilter array */
/* @var $userFilter array */

$this->title = Yii::t('app', 'Api Loggers');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <div class="box box-primary">

        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => SerialColumn::class],

                    [
                        'attribute' => 'method',
                        'filter' => $methodFilter
                    ],
                    [
                        'attribute' => 'created_at',
                        'content' => static function($data) {
                            return Yii::$app->formatter->asDatetime($data->created_at, 'php:d.m.Y H:i:s');
                        }
                    ],
                    'duration',
                    'url',
                    'app_version',
                    [
                        'attribute' => 'app_platform',
                        'filter' => $platformFilter
                    ],
                    [
                        'attribute' => 'user_id',
                        'content' => static function($data) {
                            return $data->user ? $data->user->username : null;
                        },
                        'filter' => $userFilter
                    ],
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view}'
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
