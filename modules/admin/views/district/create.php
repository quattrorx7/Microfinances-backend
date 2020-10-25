<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\District */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Районы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
