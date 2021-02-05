<?php

namespace app\modules\advance\dto;

use app\components\exceptions\UserException;
use app\helpers\PriceHelper;

class AdvanceCloseDto
{
    public $amount;
    public $day;
    public $dayPercent;
    public $summa;
    private $payment = [];

    /**
     * AdvanceDto constructor.
     * @param $advances
     * @throws UserException
     */
    public function __construct($advances)
    {
        foreach($advances as $key => $advance) {        
            $summa = 0;
            $summa_left = 0;
            $day_percent = 0;
            $day = 0;
            //Заплачено больше половиные займа
            if($advance->summa_with_percent/2 >= $advance->summa_left_to_pay) {
                //Осталось заплатить в процентах
                $per = $advance->summa_left_to_pay / $advance->summa_with_percent * 100;
                $summa_left = round($advance->amount / 100 * $per);
            }else{
            //Заплачено меньше половины, насчитываем проценты за день
                //Осталось заплатить в процентах
                $per = $advance->summa_left_to_pay / $advance->summa_with_percent * 100;
                $summa_left = round($advance->amount / 100 * $per);
                //Оставшиеся сумма за дни до середины займа
                // $summa_percent = round($advance->summa_left_to_pay - $advance->summa_with_percent / 2);

                $half = $advance->amount / 2;//5000
                $summa_half =  $advance->summa_with_percent / 2;//7500
                //Сколько платить до половины срока
                $left = $advance->summa_left_to_pay - $summa_half;//1200-7500=4500
                $summa = round($half + $left);

                $day_percent = $summa - $summa_left;
                //Количество дней, за которые надо заплатить проценты
                $day = ceil($left / $advance->daily_payment);
            }

            $this->amount += $summa_left;
            $this->dayPercent = $day_percent;
            $this->day += $day;
            $this->summa += $summa_left + $day_percent;
            $this->payment[$key] = $summa_left + $day_percent;
            
        }
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

    public function getPayment()
    {
        return $this->payment;
    }
}