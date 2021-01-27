<?php

namespace app\modules\payment\components;

use app\components\BaseService;
use app\helpers\DateHelper;
use app\models\User;
use app\modules\client\components\ClientManager;
use app\modules\client\components\ClientPayService;
use app\modules\client\dto\PayDto;
use app\modules\client\exceptions\ClientNotFoundPaymentsException;
use app\modules\client\forms\ClientPayForm;
use app\modules\client\validators\rules\HasClientPaymentsForDate;
use Exception;

class CreatePayService extends BaseService
{

    protected PaymentFactory $paymentFactory;

    protected PaymentRepository $paymentRepository;

    protected PaymentPopulator $paymentPopulator;

    protected ClientManager $clientManager;

    protected ClientPayService $clientPayService;

    public function injectDependencies(
        PaymentFactory $paymentFactory,
        PaymentRepository $paymentRepository,
        PaymentPopulator $paymentPopulator,
        ClientManager $clientManager,
        ClientPayService $clientPayService
    ): void
    {
        $this->paymentFactory = $paymentFactory;
        $this->paymentRepository = $paymentRepository;
        $this->paymentPopulator = $paymentPopulator;
        $this->clientManager = $clientManager;
        $this->clientPayService = $clientPayService;
    }

    public function execute(User $user, int $clientId, ClientPayForm $form)
    {
        $client = $this->clientManager->getClientById($clientId);
        try{
            (new HasClientPaymentsForDate())->validate($client, DateHelper::nowWithoutHours());
            
            $payDto = new PayDto($user, $client, $form);

            if($payDto->fromBalance && $payDto->amount>$client->balance){
                throw new  Exception("«Сумма» должна быть не больше баланса клиента");
            }

            $this->clientPayService->pay($payDto);

            return $payDto->getMessage();
        }catch(ClientNotFoundPaymentsException $ex){
            $payDto = new PayDto($user, $client, $form);

            if(!$payDto->fromBalance){
                $this->clientPayService->addBalance($payDto);

                return $payDto->getMessage();
            }else{
                throw $ex;
            }
        }
    }
}