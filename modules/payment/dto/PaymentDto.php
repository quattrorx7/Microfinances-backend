<?php

namespace app\modules\payment\dto;

use app\models\Payment;

class PaymentDto
{

    public $clientName;
    public $clientId;
    public $clientSurname;
    public $clientDistrict;
    public $amount;

    public static function createFromPayment(Payment $payment)
    {
        $self = new self();
        $self->amount = $payment->amount;
        $self->clientId = $payment->client_id;
        $self->clientName = $payment->client->name;
        $self->clientSurname = $payment->client->surname;
        $self->clientDistrict = $payment->district->title;

        return $self;
    }
}