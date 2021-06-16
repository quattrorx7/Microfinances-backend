<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\District */

$this->title = 'Оплатить';
$this->params['breadcrumbs'][] = ['label' => 'Платежи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-create">

    <?= $this->render('_form_pay', [
        'model' => $model,
    ]) ?>

</div>
