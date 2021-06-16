<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Клиенты';
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
            'name',
            'surname',
            'patronymic',
            'phone',
            'additional_phone',
            'district_id',
            'owner_id',
            'created_at',
            [
                'label' => 'Баланс',
                'format' => 'raw',

                'value' => function($data){
                    return $data['balance'].' <a href="/admin/client/summa?id='.$data['id'].'" title="Сумма" aria-label="Сумма" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Изменить сотрудника', 
                'headerOptions' => ['width' => '80'],
                'template' => '{change}',
                'buttons' => [
                    'change' => function ($url,$model,$key) {
                        return Html::a('Изменить', $url);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
