<?php

use app\modules\apiLogger\models\FileLoggerModel;
use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $methodFilter array */
/* @var $platformFilter array */
/* @var $userFilter array */
/* @var $filename string */
/* @var $files array */

$this->title = Yii::t('app', 'Api Loggers');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">

    <div class="box box-primary">
        <div class="box-header">
            <div class="form-group">
                <label>Выберите файл</label>
                <select class="form-control file-select">
                    <option></option>
                    <?php foreach ($files as $file) : ?>
                        <option value="<?= $file?>"><?= $file?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => SerialColumn::class],

                    [
                        'attribute' => 'method',
                        'label' => Yii::t('app', 'Method'),
                        'content' => static function(FileLoggerModel $model) {
                           return $model->getMethod();
                        },
                        'filter' => $methodFilter
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => Yii::t('app', 'Created At'),
                        'content' => static function(FileLoggerModel $model) {
                            return Yii::$app->formatter->asDatetime($model->getDateStarted(), 'php:d.m.Y H:i:s');
                        }
                    ],
                    [
                        'attribute' => 'duration',
                        'label' => Yii::t('app', 'Duration'),
                        'content' => static function(FileLoggerModel $model) {
                            return $model->getDuration();
                        }
                    ],
                    [
                        'attribute' => 'url',
                        'label' => Yii::t('app', 'Url'),
                        'content' => static function(FileLoggerModel $model) {
                            return $model->getUrl();
                        }
                    ],
                    [
                        'attribute' => 'app_version',
                        'label' => Yii::t('app', 'App Version'),
                        'content' => static function(FileLoggerModel $model) {
                            return $model->getAppVersion();
                        }
                    ],
                    [
                        'attribute' => 'app_platform',

                        'label' => Yii::t('app', 'App Platform'),
                        'content' => static function(FileLoggerModel $model) {
                            return $model->getAppPlatform();
                        }
                    ],
                    [
                        'attribute' => 'user_id',
                        'label' => Yii::t('app', 'User ID'),
                        'content' => static function(FileLoggerModel $model) use($userFilter) {
                            return $userFilter[$model->getUserId()] ?? null;
                        }
                    ],
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view}',
                        'buttons' => [
                              'view' => static function ($url, $model) use ($filename) {
                                  return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', [$url . '&filename=' . $filename], [
                                      'title' => Yii::t('yii', 'View'),
                                  ]);
                              }
                        ]
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>

<?php
$script = <<< JS
    $(".file-select").change(function() {
        let val = $(this).val();
        if (val) {
            window.location.href = '/apiLogger/show-log/index?filename='+val;
        }
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);