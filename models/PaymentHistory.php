<?php

namespace app\models;

/**
 * Class PaymentHistory
 * @package app\models
 */
class PaymentHistory extends \app\models\base\PaymentHistory
{
    public CONST PAYMENT_TYPE_AUTO = 0;
    public CONST PAYMENT_TYPE_CARD = 1;
    public CONST PAYMENT_TYPE_CASH = 2;
    public CONST PAYMENT_TYPE_BALANCE = 4;
    public CONST PAYMENT_TYPE_RETURN = 5;
    public CONST PAYMENT_TYPE_RETURNBALANCE = 6;

}