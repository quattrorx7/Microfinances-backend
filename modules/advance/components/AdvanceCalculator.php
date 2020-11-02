<?php

namespace app\modules\advance\components;

use app\components\exceptions\UserException;
use app\modules\advance\dto\AdvanceDto;

class AdvanceCalculator
{

    /**
     * @param int $amount
     * @param int $limitation
     * @param int $dailyPayment
     * @return AdvanceDto
     * @throws UserException
     */
    public function calculate(int $amount, int $limitation, int $dailyPayment): AdvanceDto
    {
        return new AdvanceDto($amount, $limitation, $dailyPayment);
    }
}