<?php

namespace app\modules\paymenthistory\components;

use app\components\BaseService;
use app\components\exceptions\UnSuccessModelException;
use app\models\PaymentHistory;
use app\modules\payment\forms\PaymentUpdateForm;
use app\modules\paymenthistory\forms\PaymentHistoryUpdateForm;

class PaymentHistoryService extends BaseService
{

    protected PaymentHistoryRepository $paymentRepository;

    protected PaymentHistoryPopulator $paymentPopulator;


    public function injectDependencies(
        PaymentHistoryRepository $paymentRepository,
        PaymentHistoryPopulator $paymentPopulator
    ): void
    {
        $this->paymentRepository = $paymentRepository;
        $this->paymentPopulator = $paymentPopulator;
    }


    /**
    * @param Payment $model
    * @param PaymentUpdateForm $form
    * @return Payment
    * @throws UnSuccessModelException
    */
    public function updateByForm(PaymentHistory $model, PaymentHistoryUpdateForm $form): PaymentHistory
    {
        $this->paymentPopulator
            ->populateFromUpdateForm($model, $form);

        $types = PaymentHistory::getTypePaymentsTitle();

        if($model->type==0){
            $model->message = '-';
        }else{
            if($model->type==PaymentHistory::PAYMENT_TYPE_BALANCE){
                $model->message = 'Резерв';
            }elseif($model->type==PaymentHistory::PAYMENT_TYPE_CARD || $model->type==PaymentHistory::PAYMENT_TYPE_CARD_BALANCE){
                $model->message = 'Перевод на карту';
            }elseif($model->type==PaymentHistory::PAYMENT_TYPE_CASH || $model->type==PaymentHistory::PAYMENT_TYPE_CASH_BALANCE){
                $model->message = 'Наличные';
            }
        }

        $this->paymentRepository->save($model);

        return $model;
    }
    
}