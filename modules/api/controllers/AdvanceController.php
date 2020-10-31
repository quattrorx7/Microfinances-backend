<?php

namespace app\modules\api\controllers;

use app\components\controllers\AuthedApiController;
use app\components\exceptions\UnSuccessModelException;
use app\components\exceptions\ValidateException;
use app\modules\advance\exceptions\AdvanceNotFoundException;
use app\modules\advance\exceptions\AdvanceStatusException;
use app\modules\advance\forms\AdvanceNoteForm;
use app\modules\advance\forms\AdvanceStatusForm;
use app\modules\advance\helpers\AdvanceHelper;
use app\modules\api\serializer\advance\AdvanceFullSerializer;
use app\modules\api\serializer\advance\AdvanceListSerializer;
use app\modules\api\serializer\advance\AdvanceSerializer;
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
            'only' => ['load-note', 'issue-loan'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['load-note', 'issue-loan'],
                    'matchCallback' => function($rule, $action){
                        return !$this->currentUser->isSuperadmin;
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
            'view' => ['GET'],
            'load-note' => ['POST']
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
     * @return array
     * @throws AdvanceStatusException
     * @throws Exception
     * @throws ValidateException
     */
    public function actionLoadNote(int $advanceId): array
    {
        $form = AdvanceNoteForm::loadAndValidate(Yii::$app->request->bodyParams);
        $model = $this->advanceService->loadNote($advanceId, $form);
        return AdvanceSerializer::serialize($model);
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
        $form = AdvanceStatusForm::loadAndValidate(Yii::$app->request->bodyParams);
        return $this->advanceService->issueAdvance($advanceId, $form);
    }
}