<?php

namespace app\modules\admin\controllers;

use app\modules\client\providers\ClientProvider;
use Yii;
use app\components\controllers\AuthedAdminController;
use app\models\District;
use app\modules\advance\components\AdvanceService;
use app\modules\advance\forms\AdvanceChangeUserForm;
use app\modules\advance\forms\AdvanceCreateByClientForm;
use app\modules\advance\forms\AdvanceSummaForm;
use app\modules\advance\providers\AdvanceProvider;
use app\modules\client\components\ClientRepository;
use app\modules\client\components\ClientService;
use app\modules\client\forms\ClientFileForm;
use app\modules\client\forms\ClientFileNoteForm;
use app\modules\payment\components\PaymentService;
use app\modules\user\components\UserRepository;
use Exception;

/**
 * Class ClientController
 * @package app\modules\admin\controllers
 */
class AdvanceController extends AuthedAdminController
{

    protected AdvanceProvider $advanceProvider;

    protected ClientProvider $clientProvider;

    protected ClientService $clientService;

    protected ClientRepository $clientRepository;

    protected AdvanceService $advanceService;

    protected UserRepository $userRepository;

    protected PaymentService $paymentService;


    public function injectDependencies(AdvanceProvider $advanceProvider, AdvanceService $advanceService, ClientProvider $clientProvider, ClientService $clientService, ClientRepository $clientRepository, UserRepository $userRepository
        , PaymentService $paymentService): void
    {
        $this->advanceProvider = $advanceProvider;
        $this->advanceService = $advanceService;

        $this->clientProvider = $clientProvider;
        $this->clientService = $clientService;
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
        $this->paymentService = $paymentService;
    }
    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        [$searchModel, $dataProvider] = $this->advanceProvider->search(Yii::$app->request->queryParams);

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
    public function actionCreate()
    {
        $form = new AdvanceCreateByClientForm();
        $clientFilesForm = new ClientFileForm();
        $clientFilesForm->load(Yii::$app->request->post());

        $formNote = new ClientFileNoteForm();
        $formNote->load(Yii::$app->request->post());

        if (Yii::$app->request->isPost){
            $form = AdvanceCreateByClientForm::loadAndValidate(Yii::$app->request->post(), null, $this->currentUser->isSuperadmin);
            
            if($clientFilesForm->validate() && $formNote->validate()) {
                $this->clientService->loadFilesFromAdminPanel($form->client_id, $clientFilesForm);
                $advance = $this->advanceService->createOldAdvance($form, $formNote, $this->currentUser);

                $this->paymentService->generatePaymentDataToDay($advance);

                return $this->redirect(['index']);
            }
        }

        $users = $this->userRepository->getWithoutAdminBySearch('');
        $clients = $this->clientRepository->getBySearch('');

        return $this->render('create', [
            'model' => $form,
            'model2' => $clientFilesForm,
            'model3' => $formNote,

            'clients' => $clients,
            'users' => $users,
        ]);
    }

    /**
     * @return JSendResponse
     * @throws ValidateAdvanceCreateException
     * @throws ValidateException
     */
    public function actionChange($id)
    {
        $form = new AdvanceChangeUserForm();
        $form->load(Yii::$app->request->post());
        $advance = $this->advanceService->getAdvance($id);

        if (Yii::$app->request->isPost && $form->validate()){
            
            $this->advanceService->changeUser($advance, $form->user_id);

            return $this->redirect(['index']);
        }else{
            $form->user_id = $advance->user_id;
        }

        $users = $this->userRepository->getWithoutAdminBySearch('');

        return $this->render('change', [
            'model' => $form,
            'users' => $users,
        ]);
    }

     /**
     * @return JSendResponse
     * @throws ValidateAdvanceCreateException
     * @throws ValidateException
     */
    public function actionSumma($id)
    {
        $form = new AdvanceSummaForm();
        $advance = $this->advanceService->getAdvance($id);
        try{
            $form = AdvanceSummaForm::loadAndValidate($advance->attributes);
        }catch(Exception $ex){
        }
        $form->load(Yii::$app->request->post());

        if (Yii::$app->request->isPost && $form->validate()){
            $advance->summa_left_to_pay = $form->summa_left_to_pay;
            $advance->update();

            return $this->redirect(['index']);
        }

        return $this->render('summa', [
            'model' => $form,
        ]);
    }
}
