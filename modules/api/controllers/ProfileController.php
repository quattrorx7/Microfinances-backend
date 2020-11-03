<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\modules\api\serializer\user\UserProfileSerializer;
use yii\base\Exception;

class ProfileController extends AuthedApiController
{

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
        return UserProfileSerializer::serialize($this->currentUser);
    }
}