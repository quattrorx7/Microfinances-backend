<?php

namespace app\modules\api\controllers;

use app\components\controllers\BaseApiController;
use app\components\JSendResponse;
use app\modules\api\serializer\advance\AdvanceSerializer;
use app\modules\advance\components\AdvanceService;
use app\modules\advance\exceptions\AdvanceNotFoundException;
use app\modules\advance\exceptions\ValidateAdvanceCreateException;
use app\modules\advance\exceptions\ValidateAdvanceUpdateException;
use app\modules\advance\forms\AdvanceCreateForm;
use app\modules\advance\forms\AdvanceUpdateForm;
use app\modules\advance\providers\AdvanceProvider;
use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;

class AdvanceController extends BaseApiController
{

    protected AdvanceService $advanceService;

    protected AdvanceProvider $advanceProvider;

    public function injectDependencies(AdvanceService $advanceService, AdvanceProvider $advanceProvider): void
    {
        $this->advanceService = $advanceService;
        $this->advanceProvider = $advanceProvider;
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
        [$searchModel, $dataProvider] = $this->advanceProvider->search(Yii::$app->request->queryParams);
        return AdvanceSerializer::serialize($dataProvider->getModels());
    }

    /**
     * @return array
     * @throws ValidateAdvanceCreateException
     * @throws Exception
     */
    public function actionCreate(): array
    {
        $form = AdvanceCreateForm::loadAndValidate(Yii::$app->request->bodyParams);
        $model = $this->advanceService->createByForm($form);

        return AdvanceSerializer::serialize($model);
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function actionView(int $id): array
    {
        $model = $this->advanceService->getAdvance($id);
        return AdvanceSerializer::serialize($model);
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     * @throws ValidateAdvanceUpdateException
     */
    public function actionUpdate(int $id): array
    {
        $form = AdvanceUpdateForm::loadAndValidate(Yii::$app->request->bodyParams);
        $model = $this->advanceService->getAdvance($id);

        $model = $this->advanceService->updateByForm($model, $form);
        return AdvanceSerializer::serialize($model);
    }

    /**
     * @param int $id
     * @return JSendResponse
     * @throws \Throwable
     * @throws AdvanceNotFoundException
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): JSendResponse
    {
        $model = $this->advanceService->getAdvance($id);
        $this->advanceService->deleteAdvance($model);

        return JSendResponse::success('Удалено');
    }

}