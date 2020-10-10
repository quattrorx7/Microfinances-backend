<?php

namespace app\modules\apiLogger\controllers;

use app\components\controllers\AuthedAdminController;
use app\modules\apiLogger\helpers\MethodFacade;
use app\modules\apiLogger\helpers\PlatformFacade;
use app\modules\apiLogger\models\search\DbLoggerModelSearch;
use app\modules\apiLogger\services\DbLoggerService;
use app\modules\user\components\UserService;
use Exception;
use Yii;

/**
 * Class DefaultController
 * @package app\modules\apiLogger\controllers
 */
class DefaultController extends AuthedAdminController
{
    protected UserService $userService;

    protected DbLoggerService $dbLoggerService;

    public function __construct($id, $module, UserService $userService, DbLoggerService $dbLoggerService, $config = [])
    {
        $this->userService = $userService;
        $this->dbLoggerService = $dbLoggerService;

        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $searchModel = new DbLoggerModelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'methodFilter' => MethodFacade::getMethods(),
            'platformFilter' => PlatformFacade::getPlatforms(),
            'userFilter' => $this->userService->mapAllByIdAndUsername()
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws Exception
     */
    public function actionView($id): string
    {
        return $this->render('view', [
            'model' => $this->dbLoggerService->findModelById($id),
        ]);
    }
}
