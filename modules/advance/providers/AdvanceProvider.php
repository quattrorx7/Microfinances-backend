<?php

namespace app\modules\advance\providers;

use app\models\search\AdvanceSearch;

class AdvanceProvider{

    /**
    * @param $params
    * @return array
    */
    public function search($params): array
    {
        $searchModel = new AdvanceSearch();
        $dataProvider = $searchModel->search($params);
        return [$searchModel, $dataProvider];
    }
}