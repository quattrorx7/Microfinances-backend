<?php

use app\modules\district\forms\DistrictUpdateForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\District */
/* @var $form DistrictUpdateForm */

$this->title = 'Редактировать';
$this->params['breadcrumbs'][] = ['label' => 'Районы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="district-update">

    <?= $this->render('_form', [
        'model' => $form,
    ]) ?>

</div>
