<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\modules\api\serializer\district\DistrictSerializer;
use app\modules\district\components\DistrictService;
use app\modules\district\providers\DistrictProvider;
use Yii;
use yii\base\Exception;

class DistrictController extends AuthedApiController
{

    protected DistrictService $districtService;

    protected DistrictProvider $districtProvider;

    public function injectDependencies(DistrictService $districtService, DistrictProvider $districtProvider): void
    {
        $this->districtService = $districtService;
        $this->districtProvider = $districtProvider;
    }

    protected function verbs(): array
    {
        return [
            'index' => ['GET']
        ];
    }

    /**
    * @return array
    * @throws Exception
    */
    public function actionIndex(): array
    {
        $result = $this->districtProvider->getAll(Yii::$app->request->queryParams);
        return DistrictSerializer::serialize($result);
    }

}