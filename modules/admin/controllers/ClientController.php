<?php

namespace app\modules\admin\controllers;

use app\modules\client\providers\ClientProvider;
use Yii;
use app\components\controllers\AuthedAdminController;

/**
 * Class ClientController
 * @package app\modules\admin\controllers
 */
class ClientController extends AuthedAdminController
{

    protected ClientProvider $clientProvider;

    public function injectDependencies(ClientProvider $clientProvider): void
    {
        $this->clientProvider = $clientProvider;
    }
    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        [$searchModel, $dataProvider] = $this->clientProvider->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
