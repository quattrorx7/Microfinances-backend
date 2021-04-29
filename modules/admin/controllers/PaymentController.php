<?php

namespace app\modules\admin\controllers;

use Yii;
use app\components\controllers\AuthedAdminController;
use app\modules\payment\components\PaymentService;
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


    public function injectDependencies(PaymentProvider $paymentProvider, PaymentService $paymentService): void
    {
        $this->paymentProvider = $paymentProvider;
        $this->paymentService = $paymentService;
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

            $this->paymentService->payFromAdminPanel($payment, $form->amount, $form->in_cart);

            return $this->redirect(['index']);
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
