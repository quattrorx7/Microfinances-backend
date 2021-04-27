<?php

namespace app\modules\payment\components;

use app\helpers\DateHelper;
use app\models\Payment;
use app\components\BaseFactory;

class PaymentFactory extends BaseFactory

{
    /**
     * @return Payment
     * @throws \Exception
     */
    public function create(): Payment
    {
        $model = new Payment();
        $model->created_at = DateHelper::now();
        return $model;
    }

    /**
     * @return Payment
     * @throws \Exception
     */
    public function createByDate($date): Payment
    {
        $model = new Payment();
        $model->created_at = $date->format('Y-m-d H:i:s');
        return $model;
    }
}