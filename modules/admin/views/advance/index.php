<?php

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
                'value' => function ($data) {
                    $client = $data->client;
                    return $client['surname'] . ' ' . $client['name']; // $data['name'] для массивов, например, при использовании SqlDataProvider.
                },
            ],
            'created_at',
            'amount',
            'limitation',
            'user_id',
            'user.fullname',
            'status',
            'daily_payment',
        ],
    ]); ?>
</div>
