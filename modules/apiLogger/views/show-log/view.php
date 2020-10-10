<?php

use app\modules\apiLogger\models\FileLoggerModel;
use yii\helpers\Json;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model FileLoggerModel */

$this->title = sprintf('%s: %s', $model->getMethod(), $model->getUrl());
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Api Loggers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-logger-view">

    <div class="box box-primary">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'created_at',
                        'value' => $model->getDateStarted()
                    ],
                    [
                        'attribute' => 'duration',
                        'value' => $model->getDuration()
                    ],
                    [
                        'attribute' => 'url',
                        'value' => $model->getUrl()
                    ],
                    [
                        'attribute' => 'method',
                        'value' => $model->getMethod()
                    ],
                    [
                        'attribute' => 'app_version',
                        'value' => $model->getAppVersion()
                    ],
                    [
                        'attribute' => 'app_platform',
                        'value' => $model->getAppPlatform()
                    ],
                    [
                        'attribute' => 'user_id',
                        'value' => $model->getUserId()
                    ]
                ],
            ]) ?>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header">
            Заголовки запроса
        </div>
        <div class="box-body">
            <pre><code class="json"><?= Json::encode($model->getHeaders()) ?></code></pre>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header">
            Данные запроса
        </div>
        <div class="box-body">
            <pre><code class="json"><?= Json::encode($model->getBodyParams()) ?></code></pre>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header">
            Ответ
        </div>
        <div class="box-body">
            <pre><code class="json"><?= Json::encode($model->getResponse()) ?></code></pre>
        </div>
    </div>

</div>
