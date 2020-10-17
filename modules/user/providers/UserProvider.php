<?php

namespace app\modules\user\providers;

use app\models\search\UserSearch;

class UserProvider{

    /**
     * @param $params
     * @param bool $hideAdmin
     * @return array
     */
    public function search($params, $hideAdmin = true): array
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($params, $hideAdmin);
        return [$searchModel, $dataProvider];
    }
}