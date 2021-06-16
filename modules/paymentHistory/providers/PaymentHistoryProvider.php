<?php

namespace app\modules\paymenthistory\providers;

use app\models\search\PaymentHistorySearch;
use app\models\search\PaymentSearch;

class PaymentHistoryProvider{

    /**
    * @param $params
    * @return array
    */
    public function search($params): array
    {
        $searchModel = new PaymentHistorySearch();
        $dataProvider = $searchModel->search($params);
        return [$searchModel, $dataProvider];
    }
}