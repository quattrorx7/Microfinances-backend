<?php

namespace app\modules\admin\controllers;

use Yii;
use app\components\controllers\AuthedAdminController;
use app\models\PaymentHistory;
use app\modules\paymenthistory\components\PaymentHistoryRepository;
use app\modules\paymenthistory\components\PaymentHistoryService;
use app\modules\paymenthistory\forms\PaymentHistoryUpdateForm;
use app\modules\paymenthistory\providers\PaymentHistoryProvider;

/**
 * Class ClientController
 * @package app\modules\admin\controllers
 */
class PaymenthistoryController extends AuthedAdminController
{

    protected PaymentHistoryProvider $paymentProvider;
    protected PaymentHistoryService $paymentService;
    protected PaymentHistoryRepository $paymentRepository;


    public function injectDependencies(PaymentHistoryProvider $paymentProvider, PaymentHistoryService $paymentService, PaymentHistoryRepository $paymentRepository): void
    {
        
        $this->paymentProvider = $paymentProvider;
        $this->paymentService = $paymentService;
        $this->paymentRepository = $paymentRepository;
    }
    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {

        [$searchModel, $dataProvider] = $this->paymentProvider->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws UnSuccessModelException
     * @throws UserNotFoundException
     * @throws ValidateUserUpdateException
     */
    public function actionUpdate($id)
    {
        $model = $this->paymentRepository->getPaymentHistoryById($id);
        $form = PaymentHistoryUpdateForm::loadAndValidate($model->attributes);

        if (Yii::$app->request->isPost) {

            $form->load(Yii::$app->request->post());
           
            $model = $this->paymentService->updateByForm($model, $form);
          
            return $this->redirect(['index', 'PaymentHistorySearch[advance_id]' => $model->advance_id]);
        }

        $types = PaymentHistory::getTypePaymentsTitle();

        return $this->render('update', [
            'model' => $form,
            'types' => $types,
        ]);
    }

     /**
     * @param $id
     * @return string|Response
     * @throws UnSuccessModelException
     * @throws UserNotFoundException
     * @throws ValidateUserUpdateException
     */
    public function actionDelete($id)
    {
        $model = $this->paymentRepository->getPaymentHistoryById($id);

        $model->delete();

        return $this->redirect(['index', 'PaymentHistorySearch[advance_id]' => $model->advance_id]);
    }

}
