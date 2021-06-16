<?php

namespace app\modules\paymenthistory\components;

use app\components\populator\AbstractPopulator;
use app\models\Advance;
use app\models\Payment;
use app\models\PaymentHistory;
use app\modules\payment\forms\PaymentCreateForm;
use app\modules\payment\forms\PaymentUpdateForm;
use app\modules\paymenthistory\forms\PaymentHistoryUpdateForm;

class PaymentHistoryPopulator extends AbstractPopulator
{

    public function populateFromUpdateForm(PaymentHistory $model, PaymentHistoryUpdateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'amount',
            'debt',
            'message',
            'type',
        ]);

        return $this;
    }

}