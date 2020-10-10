<?php

namespace app\modules\apiLogger\services;

use app\modules\apiLogger\factory\DbLoggerFactory;
use app\modules\apiLogger\helpers\ApiLoggerHelper;
use app\modules\apiLogger\jobs\DbLoggerJob;
use app\modules\apiLogger\models\DbLoggerModel;
use app\modules\apiLogger\repository\DbLoggerRepository;
use Exception;
use yii\base\BaseObject;
use yii\web\Request;
use Yii;

class DbLoggerService extends BaseObject implements ApiLoggerServiceInterface
{

    private DbLoggerFactory $dbLoggerFactory;

    private DbLoggerModel $dbLoggerModel;

    private DbLoggerRepository $dbLoggerRepository;

    public function init()
    {
        parent::init();
        $this->dbLoggerFactory = Yii::createObject(DbLoggerFactory::class);
        $this->dbLoggerRepository = Yii::createObject(DbLoggerRepository::class);
    }

    /**
     * @param Request $request
     * @throws Exception
     */
    public function exportRequest(Request $request): void
    {
        $this->dbLoggerModel = $this->dbLoggerFactory->createFromRequest($request);
    }

    /**
     * @param $response
     * @param int|null $userId
     * @throws Exception
     */
    public function exportResponse($response, ?int $userId = null): void
    {
        if (!$this->dbLoggerModel) {
            return;
        }

        $apiHelper = new ApiLoggerHelper();
        $this->dbLoggerModel->answer = $response;
        $this->dbLoggerModel->duration = round(
            $apiHelper->diff($apiHelper->getCurrentDateWithMicro(), $this->dbLoggerModel->created_at) / 1000, 2);
        if ($userId) {
            $this->dbLoggerModel->user_id = $userId;
        }

        Yii::$app->apiloggerQueue->push(
            new DbLoggerJob([
                'model' => $this->dbLoggerModel
            ])
        );
    }

    /**
     * @param $id
     * @return DbLoggerModel
     * @throws Exception
     */
    public function findModelById($id): DbLoggerModel
    {
        $model = $this->dbLoggerRepository->getById($id);

        if (!$model) {
            throw new Exception('Api logger item not Found');
        }

        return $model;
    }
}