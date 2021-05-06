<?php

namespace app\modules\admin\controllers;

use Yii;
use app\components\controllers\AuthedAdminController;
use app\modules\payment\components\PaymentRepository;
use app\modules\payment\components\PaymentService;
use app\modules\payment\forms\PaymentDebtForm;
use app\modules\payment\forms\PaymentPayForm;
use app\modules\payment\providers\PaymentProvider;

/**
 * Class ClientController
 * @package app\modules\admin\controllers
 */
class PaymentController extends AuthedAdminController
{

    protected PaymentProvider $paymentProvider;
    protected PaymentService $paymentService;
    protected PaymentRepository $paymentRepository;


    public function injectDependencies(PaymentProvider $paymentProvider, PaymentService $paymentService, PaymentRepository $paymentRepository): void
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
     * @return JSendResponse
     * @throws ValidateAdvanceCreateException
     * @throws ValidateException
     */
    public function actionPay($id)
    {
        $form = new PaymentPayForm();
        $form->load(Yii::$app->request->post());
       
        if (Yii::$app->request->isPost && $form->validate()){
            $payment = $this->paymentService->getPayment($id);

            $this->paymentService->payFromAdminPanel($payment, $form->amount, $form->date_pay." 08:00:00", $form->in_cart);

            return $this->redirect(['index', 'PaymentSearch[advance_id]'=>$payment->advance_id]);
        }else{
            $payment = $this->paymentRepository->getPaymentById($id);
            $form->date_pay = $payment->created_at;
        }
        

        return $this->render('pay', [
            'model' => $form,
        ]);
    }

    /**
     * @return JSendResponse
     * @throws ValidateAdvanceCreateException
     * @throws ValidateException
     */
    public function actionDebt($id)
    {
        $form = new PaymentDebtForm();
        $form->load(Yii::$app->request->post());
       
        if (Yii::$app->request->isPost && $form->validate()){
            $payment = $this->paymentService->getPayment($id);

            $this->paymentService->debtFromAdminPanel($payment, $form->date_pay." 06:00:00");

            return $this->redirect(['index', 'PaymentSearch[advance_id]'=>$payment->advance_id]);
        }else{
            $payment = $this->paymentRepository->getPaymentById($id);
            $form->date_pay = $payment->created_at;
        }

        return $this->render('debt', [
            'model' => $form,
        ]);
    }

    /**
     * @return JSendResponse
     * @throws ValidateAdvanceCreateException
     * @throws ValidateException
     */
    public function actionCreate()
    {
        $form = new PaymentPayForm();
        $form->load(Yii::$app->request->post());

       

        if (Yii::$app->request->isPost && $form->validate()){
            return $this->redirect(['index']);
        }


        return $this->render('create', [
            'model' => $form,
        ]);
    }
}
