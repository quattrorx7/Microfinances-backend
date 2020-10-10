<?php

namespace app\modules\apiLogger\controllers;

use app\components\controllers\AuthedAdminController;
use app\modules\apiLogger\components\LogDataProvider;
use app\modules\apiLogger\helpers\MethodFacade;
use app\modules\apiLogger\services\FileLoggerService;
use app\modules\user\components\UserService;
use Yii;

/**
 * Class ShowLogController
 * @package app\modules\apiLogger\controllers
 */
class ShowLogController extends AuthedAdminController
{
    protected UserService $userService;

    protected FileLoggerService $fileLoggerService;

    public function __construct($id, $module, UserService $userService, FileLoggerService $fileLoggerService, $config = [])
    {
        $this->userService = $userService;
        $this->fileLoggerService = $fileLoggerService;

        parent::__construct($id, $module, $config);
    }

    public function actionIndex($filename = ''): string
    {
        if (!$filename) {
            $filename = date('Ymd') . '.log';
        }

        $dataProvider = new LogDataProvider([
            'filename' => Yii::getAlias(Yii::$app->getModule('apiLogger')->params['filesDirectory'])  . $filename,
            'pagination' => ['pageSize' => 20]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'methodFilter' => MethodFacade::getMethods(),
            'userFilter' => $this->userService->mapAllByIdAndUsername(),
            'filename' => $filename,
            'files' => $this->fileLoggerService->filesInDirectory()
        ]);
    }

    /**
     * @param $id
     * @param $filename
     * @return string
     * @throws \Exception
     */
    public function actionView($id, $filename): string
    {
        return $this->render('view', [
            'model' => $this->fileLoggerService->findModelByIdAndFile($id, $filename),
        ]);
    }
}
