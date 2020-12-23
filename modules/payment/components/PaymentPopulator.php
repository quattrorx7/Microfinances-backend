<?php

namespace app\modules\payment\components;

use app\components\populator\AbstractPopulator;
use app\models\Advance;
use app\models\Payment;
use app\modules\payment\forms\PaymentCreateForm;
use app\modules\payment\forms\PaymentUpdateForm;

class PaymentPopulator extends AbstractPopulator
{

    public function populateFromCreateForm(Payment $model, PaymentCreateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [

        ]);

        return $this;
    }

    public function populateFromUpdateForm(Payment $model, PaymentUpdateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [

        ]);

        return $this;
    }

    public function populateFromAdvance(Payment $model, Advance $advance): self
    {
        $this->populateAttributes($model, 
            [
                'amount' => $advance->daily_payment,
                'full_amount' => $advance->daily_payment], 
            [
                'amount', 'full_amount'
            ]);

        $model->populateRelation('client', $advance->client);
        $model->populateRelation('advance', $advance);
        $model->populateRelation('district', $advance->client->district);
        $model->populateRelation('user', $advance->user);

        return $this;
    }

}