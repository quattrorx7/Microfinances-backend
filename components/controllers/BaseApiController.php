<?php

namespace app\components\controllers;

use app\components\serializers\ApiResponseSerializer;
use yii\rest\Controller;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class BaseApiController extends Controller
{
    public function init(): void
    {
        $this->serializer = ApiResponseSerializer::class;
        parent::init();
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class'   => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
                'application/xml'  => Response::FORMAT_XML,
            ],
        ];

        return $behaviors;
    }
}
