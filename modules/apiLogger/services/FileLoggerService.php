<?php

namespace app\modules\apiLogger\services;

use app\modules\apiLogger\factory\FileLoggerFactory;
use app\modules\apiLogger\helpers\ApiLoggerHelper;
use app\modules\apiLogger\jobs\FileLoggerJob;
use app\modules\apiLogger\models\FileLoggerModel;
use Exception;
use yii\base\BaseObject;
use yii\helpers\FileHelper;
use yii\web\Request;

class FileLoggerService extends BaseObject implements ApiLoggerServiceInterface
{

    private FileLoggerFactory $fileLoggerFactory;

    private FileLoggerModel $fileLoggerModel;

    public function init()
    {
        parent::init();
        $this->fileLoggerFactory = \Yii::createObject(FileLoggerFactory::class);
    }

    /**
     * @param Request $request
     * @throws Exception
     */
    public function exportRequest(Request $request): void
    {
        $method = $request->method;
        $url = $request->url;
        $bodyParams = $request->bodyParams;
        $headers = $request->headers;

        $this->fileLoggerModel = $this->fileLoggerFactory->createFromRequestData($method, $url, $bodyParams, $headers);
    }

    /**
     * @param $response
     * @param int|null $userId
     * @throws Exception
     */
    public function exportResponse($response, ?int $userId): void
    {
        $apiLoggerHelper = new ApiLoggerHelper();

        $this->fileLoggerModel->setUserId($userId);
        $this->fileLoggerModel->setResponse($response);
        $this->fileLoggerModel->setDateEnded($apiLoggerHelper->getCurrentDateWithMicro());

        \Yii::$app->apiloggerQueue->push(
            new FileLoggerJob([
                'model' => $this->fileLoggerModel
            ])
        );
    }

    /**
     * @param $id
     * @param $filename
     * @return FileLoggerModel
     * @throws Exception
     */
    public function findModelByIdAndFile($id, $filename): FileLoggerModel
    {
        $directory = \Yii::$app->getModule('apiLogger')->params['filesDirectory'];

        $fileObject = new \SplFileObject(\Yii::getAlias($directory) . $filename);
        $fileObject->seek($id);

        if (!$fileObject->current()) {
            throw new Exception('Line not found');
        }

        return $this->fileLoggerFactory->createFromLogLine($fileObject->current());
    }

    public function filesInDirectory(): array
    {
        $directory = \Yii::getAlias(\Yii::$app->getModule('apiLogger')->params['filesDirectory']);
        $files = FileHelper::findFiles($directory);

        return array_map(static function ($item) {
            $last_arr=explode('/', $item);
            return $last_arr[count($last_arr)-1];
        }, $files);
    }
}