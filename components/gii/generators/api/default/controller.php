<?php

/** @var app\components\gii\generators\api\Generator $generator */

echo "<?php\n";
?>

namespace app\modules\api\controllers;

use app\components\controllers\BaseApiController;
use app\components\JSendResponse;
use app\modules\api\serializer\<?= $generator->modelLowerCamelCase?>\<?= $generator->modelClass?>Serializer;
use <?= $generator->moduleNamespace?>\<?= $generator->modelClass?>Service;
use <?= $generator->exceptionsNamespace?>\<?= $generator->notFoundExceptionClass?>;
use <?= $generator->exceptionsNamespace?>\<?= $generator->createExceptionClass?>;
use <?= $generator->exceptionsNamespace?>\<?= $generator->updateExceptionClass?>;
use <?= $generator->formsNamespace?>\<?= $generator->createFormClass?>;
use <?= $generator->formsNamespace?>\<?= $generator->updateFormClass?>;
use <?= $generator->providersNamespace?>\<?= $generator->modelClass?>Provider;
use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;

class <?= $generator->apiControllerClass?> extends BaseApiController
{

    protected <?= $generator->serviceClass?> $<?= $generator->modelLowerCamelCase?>Service;

    protected <?= $generator->providerClass?> $<?= $generator->modelLowerCamelCase?>Provider;

    public function injectDependencies(<?= $generator->serviceClass?> $<?= $generator->modelLowerCamelCase?>Service, <?= $generator->providerClass?> $<?= $generator->modelLowerCamelCase?>Provider): void
    {
        $this-><?= $generator->modelLowerCamelCase?>Service = $<?= $generator->modelLowerCamelCase?>Service;
        $this-><?= $generator->modelLowerCamelCase?>Provider = $<?= $generator->modelLowerCamelCase?>Provider;
    }

    protected function verbs(): array
    {
        return [
            'index' => ['GET'],
            'create' => ['POST'],
            'view' => ['GET'],
            'update' => ['POST'],
            'delete' => ['DELETE']
        ];
    }

    /**
    * @return array
    * @throws Exception
    */
    public function actionIndex(): array
    {
        [$searchModel, $dataProvider] = $this-><?= $generator->modelLowerCamelCase?>Provider->search(Yii::$app->request->queryParams);
        return <?= $generator->modelClass?>Serializer::serialize($dataProvider->getModels());
    }

    /**
     * @return array
     * @throws <?= $generator->createExceptionClass?>

     * @throws Exception
     */
    public function actionCreate(): array
    {
        $form = <?= $generator->createFormClass?>::loadAndValidate(Yii::$app->request->bodyParams);
        $model = $this-><?= $generator->modelLowerCamelCase?>Service->createByForm($form);

        return <?= $generator->modelClass?>Serializer::serialize($model);
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function actionView(int $id): array
    {
        $model = $this-><?= $generator->modelLowerCamelCase?>Service->get<?= $generator->modelClass?>($id);
        return <?= $generator->modelClass?>Serializer::serialize($model);
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     * @throws <?= $generator->updateExceptionClass?>

     */
    public function actionUpdate(int $id): array
    {
        $form = <?= $generator->updateFormClass?>::loadAndValidate(Yii::$app->request->bodyParams);
        $model = $this-><?= $generator->modelLowerCamelCase?>Service->get<?= $generator->modelClass?>($id);

        $model = $this-><?= $generator->modelLowerCamelCase?>Service->updateByForm($model, $form);
        return <?= $generator->modelClass?>Serializer::serialize($model);
    }

    /**
     * @param int $id
     * @return JSendResponse
     * @throws \Throwable
     * @throws <?= $generator->notFoundExceptionClass?>

     * @throws StaleObjectException
     */
    public function actionDelete(int $id): JSendResponse
    {
        $model = $this-><?= $generator->modelLowerCamelCase?>Service->get<?= $generator->modelClass?>($id);
        $this-><?= $generator->modelLowerCamelCase?>Service->delete<?= $generator->modelClass?>($model);

        return JSendResponse::success('Удалено');
    }

}