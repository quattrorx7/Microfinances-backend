<?php

namespace app\controllers;

use app\components\constants\UrlConst;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => '@app/views/layouts/empty'
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(UrlConst::LOGIN_ADMIN_PAGE);
    }

    public function actionDocs(): void
    {
        Yii::$app->response->sendFile('../docs/micro.postman_collection.json');
    }

}
