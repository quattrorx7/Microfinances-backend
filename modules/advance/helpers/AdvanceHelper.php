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
}