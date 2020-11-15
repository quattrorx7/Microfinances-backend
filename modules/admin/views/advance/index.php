<?php

use app\models\Advance;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Займы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'client_id',
            [
                'label' => 'Клиент',
                'value' => function ($data) {
                    $client = $data->client;
                    return $client['surname'] . ' ' . $client['name']; // $data['name'] для массивов, например, при использовании SqlDataProvider.
                },
            ],
            'created_at',
            'amount',
            'daily_payment',
            'user_id',
            [
                'label' => 'Сотрудник (Пользователь)',
                'value' => 'user.fullname'
            ],
            'status',
            [
                'label' => 'Статус',
                'value' => function ($data) {
                    if($data['status']==Advance::STATE_SENT) return 'Отправлена';
                    elseif($data['status']==Advance::STATE_DENIED) return 'Отказано';
                    elseif($data['status']==Advance::STATE_APPROVED) return 'Одобрено';
                    elseif($data['status']==Advance::STATE_ISSUED){
                        if($data['payment_status']==Advance::PAYMENT_STATUS_STARTED) return 'Текущий';
                        if($data['payment_status']==Advance::PAYMENT_STATUS_CLOSED) return 'Закрыт'; 
                    } 


                },
            ],
           

            // [
            //     'class' => 'yii\grid\ActionColumn',
            //     'header'=>'Действия', 
            //     'headerOptions' => ['width' => '80'],
            //     'template' => '{view} {update} {delete}{link}',
            //     'buttons' => [
                
            //         'link' => function ($url,$model,$key) {
            //             return Html::a('Оплатить', $url);
            //         },
            //     ],

            // ],

            [
                'label' => 'Платежи',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        'Перейти',
                        '/admin/payment/index?PaymentSearch[advance_id]='.$data->id,
                        [
                            'title' => '',
                            'target' => '_blank'
                        ]
                    );
                }
            ],
        ],
    ]); ?>
</div>
