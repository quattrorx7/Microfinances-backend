<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Advance */

$this->title = 'Create Advance';
$this->params['breadcrumbs'][] = ['label' => 'Advances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advance-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
