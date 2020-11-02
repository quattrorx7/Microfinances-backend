<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\components\exceptions\UnSuccessModelException;
use app\components\exceptions\UserException;
use app\modules\advance\exceptions\AdvanceNotFoundException;
use app\modules\advance\exceptions\AdvanceStatusException;
use app\modules\advance\exceptions\ValidateAdvanceCreateException;
use app\modules\advance\forms\AdvanceApprovedForm;
use app\modules\advance\forms\AdvanceNoteForm;
use app\modules\advance\helpers\AdvanceHelper;
use app\modules\api\serializer\advance\AdvanceFullSerializer;
use app\modules\api\serializer\advance\AdvanceListSerializer;
use app\modules\advance\components\AdvanceService;
use app\modules\advance\providers\AdvanceProvider;
use app\modules\api\serializer\advance\AdvanceShortSerializer;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class AdvanceController extends AuthedApiController
{

    protected AdvanceService $advanceService;

    protected AdvanceProvider $advanceProvider;

    public function injectDependencies(AdvanceService $advanceService, AdvanceProvider $advanceProvider): void
    {
        $this->advanceService = $advanceService;
        $this->advanceProvider = $advanceProvider;
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
            'percent' => ['GET'],
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
     * @return string
     * @throws AdvanceNotFoundException
     * @throws AdvanceStatusException
     * @throws UnSuccessModelException
     */
    public function actionDenied(int $advanceId): string
    {
        $this->advanceService->deniedAdvance($advanceId);
        return 'В заявке отказано';
    }

    /**
     * @param int $advanceId
     * @return string
     * @throws AdvanceNotFoundException
     * @throws AdvanceStatusException
     * @throws UnSuccessModelException
     * @throws UserException
     * @throws ValidateAdvanceCreateException
     */
    public function actionApproved(int $advanceId): string
    {
        $form = AdvanceApprovedForm::loadAndValidate(\Yii::$app->request->bodyParams);

        $this->advanceService->approvedAdvance($advanceId, $form);
        return 'Одобрено';
    }

    /**
     * @param int $advanceId
     * @return string
     * @throws AdvanceStatusException
     * @throws UnSuccessModelException
     * @throws AdvanceNotFoundException
     */
    public function actionIssueLoan(int $advanceId): string
    {
        $form = AdvanceNoteForm::loadAndValidate(Yii::$app->request->bodyParams);
        $this->advanceService->issueAdvance($advanceId, $form);

        return 'Займ выдан';
    }

    public function actionPercent(int $advanceId): array
    {
        $advanceDto = $this->advanceService->getAdvanceDto($advanceId);
        return $advanceDto->getAttributes();
    }
}