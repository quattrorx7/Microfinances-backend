<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\District */

$this->title = 'Создать Займ';
$this->params['breadcrumbs'][] = ['label' => 'Займы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-create">

    <?= $this->render('_form_create', [
        'model' => $model,
        'model2' => $model2,
        'model3' => $model3,
        'clients' => $clients,
        'users' => $users,
    ]) ?>

</div>
