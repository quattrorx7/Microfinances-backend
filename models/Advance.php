<?php

namespace app\models;

/**
 * Class Advance
 * @package app\models
 */
class Advance extends \app\models\base\Advance
{
    public CONST DAYS_15 = 15;
    public CONST DAYS_30 = 30;
    public CONST DAYS_45 = 45;
    public CONST DAYS_60 = 60;

    public CONST STATE_SENT = 'sent';
    public CONST STATE_DENIED = 'denied';
    public CONST STATE_APPROVED = 'approved';
    public CONST STATE_ISSUED = 'issued';
}