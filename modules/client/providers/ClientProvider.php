<?php

namespace app\modules\client\providers;

use app\models\search\ClientSearch;

class ClientProvider{

    /**
    * @param $params
    * @return array
    */
    public function search($params): array
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search($params);
        return [$searchModel, $dataProvider];
    }
}