<?php

namespace app\modules\client\helpers;

class ClientPayHelper
{
    public static function differenceResult(int $allAmount, int $payAmount): int
    {
        return $allAmount - $payAmount > 0 ? $payAmount : $allAmount;
    }
}