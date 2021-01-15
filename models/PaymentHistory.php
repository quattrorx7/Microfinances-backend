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
    public CONST PAYMENT_TYPE_CARD_BALANCE = 4;
    public CONST PAYMENT_TYPE_CASH_BALANCE = 5;
    public CONST PAYMENT_TYPE_RETURN = 6;
    public CONST PAYMENT_TYPE_RETURNBALANCE = 7;

    public static function getTypePayments()
    {
        return [
            self::PAYMENT_TYPE_CARD,
            self::PAYMENT_TYPE_CASH,
        ];
    }

    public static function getTypePaymentsWithBalance()
    {
        return [
            self::PAYMENT_TYPE_CARD,
            self::PAYMENT_TYPE_CASH,
            self::PAYMENT_TYPE_CARD_BALANCE,
            self::PAYMENT_TYPE_CASH_BALANCE,
        ];
    }

}