<?php

use yii\queue\amqp_interop\Queue;

return [
    'class'     => Queue::class,
    'exchangeName' => 'apilogger',
    'port'      => 5672,
    'user'      => 'usermq',
    'password'  => 'hruiW3mq',
    'queueName' => 'savelog',
    'ttr'       => 5 * 60,
    'attempts'  => 3,
    'driver'    => Queue::ENQUEUE_AMQP_LIB
];