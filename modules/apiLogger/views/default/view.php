<?php

use app\modules\apiLogger\models\DbLoggerModel;
use yii\helpers\Json;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model DbLoggerModel*/

$this->title = sprintf('%s: %s', $model->method, $model->url);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Api Loggers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-logger-view">

    <div class="box box-primary">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'created_at',
                    'duration',
                    'url',
                    'method',
//                    'request',
//                    'headers',
//                    'answer',
                    'app_version',
                    'app_platform',
                    'user_id',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header">
            Заголовки запроса
        </div>
        <div class="box-body">
            <pre><code class="json"><?= Json::encode($model->headers) ?></code></pre>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header">
            Данные запроса
        </div>
        <div class="box-body">
            <pre><code class="json"><?= Json::encode($model->request) ?></code></pre>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header">
            Ответ
        </div>
        <div class="box-body">
            <pre><code class="json"><?= Json::encode($model->answer) ?></code></pre>
        </div>
    </div>

</div>
