<?php

namespace app\modules\advance\helpers;

use app\models\Advance;

class AdvanceHelper
{
    public static function getStatusById($statusId): string
    {
        $statuses = self::getStatuses();
        return $statuses[$statusId];
    }

    public static function getStatuses(): array
    {
        return [
            Advance::STATE_SENT => 'Отправлена',
            Advance::STATE_DENIED => 'Отказано',
            Advance::STATE_APPROVED => 'Одобрено',
            Advance::STATE_ISSUED => 'Выдано',
        ];
    }

    public static function getShortStatusById($statusId, $paymentId): string
    {
        if($statusId==Advance::STATE_ISSUED){
            return (self::getShortPaymentStatuses())[$paymentId];
        }else{
            return (self::getShortStatuses())[$statusId];
        }
    }

    public static function getShortStatuses(): array
    {
        return [
            Advance::STATE_SENT => 'Отправлена',
            Advance::STATE_DENIED => 'Отказ',
            Advance::STATE_APPROVED => 'Одобрено',
            Advance::STATE_ISSUED => 'Текущий',
        ];
    }

    public static function getShortPaymentStatuses(): array
    {
        return [
            Advance::PAYMENT_STATUS_NULL => " ",
            Advance::PAYMENT_STATUS_CLOSED => 'Закрыт',
            Advance::PAYMENT_STATUS_STARTED => 'Текущий',
        ];
    }
}