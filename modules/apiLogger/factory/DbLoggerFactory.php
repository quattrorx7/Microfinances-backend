<?php

namespace app\modules\apiLogger\factory;

use app\modules\apiLogger\helpers\ApiLoggerHelper;
use app\modules\apiLogger\models\DbLoggerModel;
use Exception;
use yii\base\BaseObject;
use yii\web\Request;

class DbLoggerFactory extends BaseObject
{
    /**
     * @param Request $request
     * @return DbLoggerModel
     * @throws Exception
     */
    public function createFromRequest(Request $request): DbLoggerModel
    {
        $model = new DbLoggerModel();

        $model->attributes = [
            'method' => strtoupper($request->method),
            'url' => $request->url,
            'created_at' => (new ApiLoggerHelper())->getCurrentDateWithMicro(),
            'request' => $request->bodyParams,
            'headers' => $request->headers->toArray(),
            'app_platform' => $request->headers->has('App-Platform') ? strtolower($request->headers->get('App-Platform')) : null,
            'app_version' => $request->headers->has('App-Version') ? $request->headers->get('App-Version') : null
        ];

        return $model;
    }
}