<?php

namespace app\modules\admin\controllers;

use Yii;
use app\components\controllers\AuthedAdminController;
use app\modules\advance\components\AdvanceRepository;
use app\modules\advance\providers\AdvanceProvider;
use app\modules\payment\components\PaymentRepository;
use app\modules\payment\components\PaymentService;
use app\modules\payment\forms\PaymentCreateForm;
use app\modules\payment\forms\PaymentDebtForm;
use app\modules\payment\forms\PaymentPayForm;
use app\modules\payment\forms\PaymentSummaForm;
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
    protected AdvanceRepository $advanceRepository;


    public function injectDependencies(PaymentProvider $paymentProvider, PaymentService $paymentService, PaymentRepository $paymentRepository, AdvanceRepository $advanceRepository): void
    {
        $this->paymentProvider = $paymentProvider;
        $this->paymentService = $paymentService;
        $this->paymentRepository = $paymentRepository;
        $this->advanceRepository = $advanceRepository;
    }
    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        [$searchModel, $dataProvider] = $this->paymentProvider->search(Yii::$app->request->queryParams);

        $req = Yii::$app->request->queryParams;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'advance_id' => $req['PaymentSearch']['advance_id']??null,
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
    public function actionCreate($id)
    {
        $form = new PaymentCreateForm();
        $form->load(Yii::$app->request->post());

        $advance = $this->advanceRepository->getAdvanceById($id);

        if($advance->payment_left==0){
            throw new \yii\Web\HttpException(420, 'Все платежи уже созданы');
        }

        if (Yii::$app->request->isPost && $form->validate()) {
            $this->paymentService->generatePayment($advance, $form->date_pay);

            return $this->redirect(['index', 'PaymentSearch[advance_id]'=>$advance->id]);
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * @return JSendResponse
     * @throws ValidateAdvanceCreateException
     * @throws ValidateException
     */
    public function actionSumma($id)
    {
        $form = new PaymentSummaForm();
        $payment = $this->paymentService->getPayment($id);

        $form = PaymentSummaForm::loadAndValidate($payment->attributes);
        $form->load(Yii::$app->request->post());

        if (Yii::$app->request->isPost && $form->validate()){
            $payment->amount = $form->amount;
            $payment->update();

            return $this->redirect(['index', 'PaymentSearch[advance_id]'=>$payment->advance_id]);
        }

        return $this->render('summa', [
            'model' => $form,
        ]);
    }
}
