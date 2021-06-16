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
    public CONST PAYMENT_TYPE_BALANCE = 6;
    public CONST PAYMENT_TYPE_RETURN = 7;
    public CONST PAYMENT_TYPE_RETURNBALANCE = 8;

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

    /**
     * Все способы оплаты
     */
    public static function getTypePaymentsAll()
    {
        return [
            self::PAYMENT_TYPE_CARD,
            self::PAYMENT_TYPE_CASH,
            self::PAYMENT_TYPE_BALANCE,
        ];
    }

    public static function getTypePaymentsTitle()
    {
        return [
            0 => 'Автоматический',
            1 => 'С карты',
            2 => 'Наличными',
            4 => 'С карты на баланс',
            5 => 'Наличными на баланс',
            6 => 'С баланса',
            // 7 => 'Возврат',
            // 8 => 'Возврат на баланс',
        ];
    }

}