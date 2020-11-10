<?php

namespace app\modules\payment\providers;

use app\models\search\PaymentSearch;

class PaymentProvider{

    /**
    * @param $params
    * @return array
    */
    public function search($params): array
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search($params);
        return [$searchModel, $dataProvider];
    }
}