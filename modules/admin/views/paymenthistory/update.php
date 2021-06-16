<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\District */

$this->title = 'Редактировать транзакцию';
$this->params['breadcrumbs'][] = ['label' => 'Транзакции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-create">

    <?= $this->render('_form_update', [
        'model' => $model,
        'types' => $types,
    ]) ?>

</div>
