<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DistrictSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Транзакции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-index">
    <p>
        <!-- <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?> -->
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            'amount',
            'debt',
            
            'advance_id',

            'created_at',

            'message',

              
            // [
            //     'class' => 'yii\grid\ActionColumn',
            //     'header'=>'Действия', 
            //     'headerOptions' => ['width' => '80'],
            //     'template' => '{edit}',
            //     'buttons' => [
            //         'edit' => function ($url,$model,$key) {
            //             return Html::a('Изменить', $url);
            //         },
            //     ],

            // ],

            ['class' => yii\grid\ActionColumn::class, 'template' => '{update} {delete}'],
           
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
