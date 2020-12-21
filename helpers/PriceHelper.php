<?php

namespace app\helpers;

class PriceHelper
{
    /**
     * Форматирование цены
     * 
     * @param float $price - цена
     */
    public static function priceFormat($price): string
    {
        return number_format($price, 0, '.', ' ').' ₽';
    }

}