<?php

namespace app\components\events;

use yii\base\Event;

class ErrorHandlerEvent extends Event
{
    /**
     * @var mixed
     */
    public $result;

    /**
     * @var mixed
     */
    public $debug;
}
