<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DistrictSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Платежи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-index">
    <p>
        <? if($advance_id){ ?>
        <?= Html::a('Добавить', ['create?id='.$advance_id], ['class' => 'btn btn-success']) ?>
        <? } ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'client_id',
            [
                'label' => 'Клиент',
                'value' => function ($data) {
                    $client = $data->client;
                    return $client['surname'] . ' ' . $client['name']; // $data['name'] для массивов, например, при использовании SqlDataProvider.
                },
            ],
            // 'amount',
            [
                'label' => 'Осталось заплатить',
                'format' => 'raw',

                'value' => function($data){
                    return $data['amount'].' <a href="/admin/payment/summa?id='.$data['id'].'" title="Сумма" aria-label="Сумма" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>';
                }
            ],

            'full_amount',
            'user_id',
            [
                'label' => 'Сотрудник (Пользователь)',
                'value' => 'user.fullname'
            ],
            'advance_id',
            [
                'label' => 'Займ ID',
                'value' => 'advance_id'
            ],

            'created_at',
              
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Действия', 
                'headerOptions' => ['width' => '80'],
                'template' => '{pay} {debt}',
                'buttons' => [
                    'pay' => function ($url,$model,$key) {
                        return Html::a('Оплатить', $url);
                    },
                    'debt' => function ($url,$model,$key) {
                        if($model->amount == $model->full_amount)
                            return Html::a('Долг', $url);
                        else
                            return '-';
                    },
                ],

            ],

           
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
