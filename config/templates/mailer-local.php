<?php
return [
    'class' => 'yii\swiftmailer\Mailer',
    'useFileTransport' => false,
    'messageConfig' => [
        'charset' => 'UTF-8',
        'from' => [$params['senderEmail'] => $params['senderName']],
    ],
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => $params['senderHost'],
        'port' => 25
    ],
];
