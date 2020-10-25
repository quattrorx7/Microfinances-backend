<?php

namespace app\modules\district\providers;

use app\models\search\DistrictSearch;

class DistrictProvider{

    /**
    * @param $params
    * @return array
    */
    public function search($params): array
    {
        $searchModel = new DistrictSearch();
        $dataProvider = $searchModel->search($params);
        return [$searchModel, $dataProvider];
    }

    public function getAll($params)
    {
        $searchModel = new DistrictSearch();
        return $searchModel->searchAll($params);
    }
}