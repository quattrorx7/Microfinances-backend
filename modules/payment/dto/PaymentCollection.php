<?php

namespace app\modules\payment\dto;

use app\models\Payment;
use app\modules\api\serializer\payment\PaymentSerializer;

class PaymentCollection
{
    public array $payments = [];

    public function __construct(array $payments)
    {
        foreach ($payments as $payment) {
            $this->add($payment);
        }
    }

    public function add(Payment $payment): void
    {
        $this->payments[$payment->district_id]['title'] = $payment->district->title;
        $this->payments[$payment->district_id]['payments'][] = PaymentSerializer::serialize($payment);
    }
}