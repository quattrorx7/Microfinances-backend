<?php

namespace app\modules\advance\dto;

use app\components\exceptions\UserException;
use app\helpers\PriceHelper;

class AdvanceDto
{
    public $limitation;
    public $amount;
    public $daily_payment;
    public $summa_with_percent;
    public $percent;

    /**
     * AdvanceDto constructor.
     * @param $limitation
     * @param $amount
     * @param $daily_payment
     * @throws UserException
     */
    public function __construct(int $amount, int $limitation, int $daily_payment)
    {
        $this->limitation = $limitation;
        $this->amount = $amount;
        $this->daily_payment = $daily_payment;

        $this->summa_with_percent = round($daily_payment * $limitation);

        if ($this->summa_with_percent < $this->amount) {
            $daily_payment = ceil($this->amount / $limitation);
            throw new UserException('Минимальная сумма ' . PriceHelper::priceFormat($daily_payment) . ' - это займ поделенный на кол-во дней');
        }

        $this->percent = round(100 - 100 * $amount / $this->summa_with_percent, 2);
    }

    public function attributes(): array
    {
        $class = new \ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }

        return $names;
    }

    public function getAttributes($names = null, $except = []): array
    {
        $values = [];
        if ($names === null) {
            $names = $this->attributes();
        }
        foreach ($names as $name) {
            $values[$name] = $this->$name;
        }
        foreach ($except as $name) {
            unset($values[$name]);
        }

        return $values;
    }
}