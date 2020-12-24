<?php

namespace app\modules\payment\components;

use app\components\BaseService;
use app\helpers\DateHelper;
use app\models\Client;
use app\models\Payment;
use app\models\PaymentHistory;

class PaymentHistoryService extends BaseService
{

    public function saveHistory(Client $client, Payment $payment, int $amount, $inCart, string $actor = 'payment'): void
    {
        if ($amount > 0 || $inCart===null) {
            $model = new PaymentHistory();

            $model->payment_id = $payment->id;
            $model->advance_id = $payment->advance_id;
            $model->client_id = $payment->client_id;
            $model->amount = $amount;
            if($inCart===null){
                $model->message = '-';
            }else{
                $model->message = $inCart ? 'Перевод на карту' : 'Наличные';
            }
            $model->created_at = DateHelper::now();
            $model->actor = $actor;
            $model->debt = $client->getAllDebts();

            $model->save();
        }
    }

    public function getHistoryByClientId(int $clientId): array
    {
        return PaymentHistory::find()
            ->where(['client_id' => $clientId])
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }

}