<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\components\exceptions\UnSuccessModelException;
use app\components\exceptions\UserException;
use app\components\exceptions\ValidateException;
use app\components\JSendResponse;
use app\modules\advance\components\AdvanceManager;
use app\modules\advance\exceptions\AdvanceNotFoundException;
use app\modules\advance\exceptions\AdvanceStatusException;
use app\modules\advance\exceptions\ValidateAdvanceCreateException;
use app\modules\advance\forms\AdvanceApprovedForm;
use app\modules\advance\forms\AdvanceCreateByClientForm;
use app\modules\advance\forms\AdvanceNoteForm;
use app\modules\advance\forms\AdvancePercentForm;
use app\modules\advance\helpers\AdvanceHelper;
use app\modules\api\serializer\advance\AdvanceClientSerializer;
use app\modules\api\serializer\advance\AdvanceDebtSerializer;
use app\modules\api\serializer\advance\AdvanceFullSerializer;
use app\modules\api\serializer\advance\AdvanceListSerializer;
use app\modules\advance\components\AdvanceService;
use app\modules\advance\providers\AdvanceProvider;
use app\modules\api\serializer\advance\AdvanceHistorySerializer;
use app\modules\api\serializer\advance\AdvanceShortSerializer;
use app\modules\client\components\ClientService;
use app\modules\client\forms\ClientFileForm;
use app\modules\client\forms\ClientSearchForm;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class AdvanceController extends AuthedApiController
{

    protected AdvanceService $advanceService;

    protected ClientService $clientService;

    protected AdvanceProvider $advanceProvider;

    protected AdvanceManager $advanceManager;

    public function injectDependencies(
        AdvanceService $advanceService,
        AdvanceProvider $advanceProvider,
        ClientService $clientService,
        AdvanceManager $advanceManager
    ): void
    {
        $this->advanceService = $advanceService;
        $this->advanceProvider = $advanceProvider;
        $this->clientService = $clientService;
        $this->advanceManager = $advanceManager;
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['issue-loan', 'denied', 'approved', 'percent'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['issue-loan'],
                    'matchCallback' => function($rule, $action){
                        return !$this->currentUser->isSuperadmin;
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['denied', 'approved', 'percent'],
                    'matchCallback' => function($rule, $action){
                        return $this->currentUser->isSuperadmin;
                    }
                ],
            ],
        ];
        return $behaviors;
    }

    protected function verbs(): array
    {
        return [
            'index' => ['GET'],
            'status' => ['GET'],
            'view' => ['GET'],
            'issue-loan' => ['POST'],
            'denied' => ['POST'],
            'approved' => ['POST'],
            'percent' => ['POST'],
            'create' => ['POST'],
            'archive' => ['GET'],
        ];
    }

    /**
    * @return array
    * @throws Exception
    */
    public function actionIndex(): array
    {
        $advances = $this->isSuperadmin($this->currentUser) ?
            $this->advanceService->searchLast() :
            $this->currentUser->lastAdvances;

        return AdvanceListSerializer::serialize($advances);
    }

    public function actionArchive(): array
    {
        $form = ClientSearchForm::loadAndValidate(Yii::$app->request->queryParams);
        $advances = $this->advanceManager->search($form, $this->currentUser);

        return AdvanceClientSerializer::serialize($advances);
    }

    public function actionDebt(): array
    {
        $advances = $this->advanceManager->debts($this->currentUser);

        return AdvanceDebtSerializer::serialize($advances);
    }

    public function actionStatus(): array
    {
        return AdvanceHelper::getStatuses();
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function actionView(int $id): array
    {
        $model = $this->advanceService->getAdvance($id);

        if ($this->isSuperadmin($this->currentUser) && $model->isSent()) {
            return AdvanceFullSerializer::serialize($model);
        }

        if ($model->isApproved() && $model->isOwner($this->currentUser)) {
            return AdvanceShortSerializer::serialize($model);
        }

        throw new ForbiddenHttpException('Доступ запрещен');
    }

    /**
     * @param int $advanceId
     * @return JSendResponse
     * @throws AdvanceNotFoundException
     * @throws AdvanceStatusException
     * @throws UnSuccessModelException
     */
    public function actionDenied(int $advanceId): JSendResponse
    {
        $this->advanceService->deniedAdvance($advanceId);
        return JSendResponse::success('В заявке отказано');
    }

    /**
     * @param int $advanceId
     * @return JSendResponse
     * @throws AdvanceNotFoundException
     * @throws AdvanceStatusException
     * @throws UnSuccessModelException
     * @throws UserException
     * @throws ValidateAdvanceCreateException
     */
    public function actionApproved(int $advanceId): JSendResponse
    {
        $form = AdvanceApprovedForm::loadAndValidate(\Yii::$app->request->bodyParams);
        $this->advanceService->approvedAdvance($advanceId, $form);
        return JSendResponse::success('Одобрено');
    }

    /**
     * @param int $advanceId
     * @return JSendResponse
     * @throws AdvanceNotFoundException
     * @throws AdvanceStatusException
     * @throws ValidateException
     */
    public function actionIssueLoan(int $advanceId): JSendResponse
    {
        $form = AdvanceNoteForm::loadAndValidate(Yii::$app->request->bodyParams);
        $this->advanceService->issueAdvance($advanceId);

        return JSendResponse::success('Займ выдан');
    }

    /**
     * @param int $advanceId
     * @return array
     * @throws ValidateAdvanceCreateException
     */
    public function actionPercent(int $advanceId): array
    {
        $form = AdvancePercentForm::loadAndValidate(Yii::$app->request->bodyParams);
        $advanceDto = $this->advanceService->calculate($form->amount, $form->limitation, $form->daily_payment);

        return $advanceDto->getAttributes();
    }

    /**
     * @return JSendResponse
     * @throws ValidateAdvanceCreateException
     * @throws ValidateException
     */
    public function actionCreate(): JSendResponse
    {
        $form = AdvanceCreateByClientForm::loadAndValidate(Yii::$app->request->bodyParams, '', $this->currentUser->isSuperadmin);
        $clientFilesForm = ClientFileForm::loadAndValidate(Yii::$app->request->bodyParams);

        $this->clientService->loadFiles($form->client_id, $clientFilesForm);
        $result = $this->advanceService->createByClientForm($form, $this->currentUser);

        return JSendResponse::success($result);
    }

    /**
     * История займов
     */
    public function actionHistory(int $clientId)
    {
        $history = $this->advanceService->getHistoryByClientId($clientId);

        return AdvanceHistorySerializer::serialize($history);
    }
}