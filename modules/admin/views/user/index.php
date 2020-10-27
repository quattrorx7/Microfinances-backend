<?php

use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <div class="box box-primary">
        <div class="box-header with-border">
            <?= Html::a(Yii::t('app',  'Create User'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        </div>

        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'fullname',
                    'username',

                    ['class' => ActionColumn::class, 'template' => '{view} {update}'],
                ],
            ]) ?>
        </div>
    </div>
</div>
