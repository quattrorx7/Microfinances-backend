<?php
return [
    [
        'class'       => yii\web\GroupUrlRule::class,
        'prefix'      => 'api',
        'routePrefix' => 'api/<controller>',
        'rules'       => [
            'GET <controller>'                   => 'index',
            'POST <controller>'                  => 'create',
            'POST <controller>/<id:\d+>'         => 'update',
            'GET <controller>/<id:\d+>'          => 'view',
            'DELETE <controller>/<id:\d+>'       => 'delete',
        ],
    ],

    #загрузка расписки
    'POST api/advance/<advanceId:\d+>/load-note' => 'api/advance/load-note',

    #выдача займа
    'POST api/advance/<advanceId:\d+>/issue-loan' => 'api/advance/issue-loan',

    #статусы заявок
    'POST api/advance/status' => 'api/advance/status',

    #отказать в заявке
    'POST api/advance/<advanceId:\d+>/denied' => 'api/advance/denied',

    #принять оплату
    'POST api/payment/<clientId:\d+>/pay' => 'api/payment/pay',

    #Возврат платежа
    'GET api/payment/<paymentId:\d+>/return' => 'api/payment/return',

    #история оплат
    'GET api/payment/<clientId:\d+>/history' => 'api/payment/history',

    #одобрить заявку
    'POST api/advance/<advanceId:\d+>/approved' => 'api/advance/approved',

    #Создание рефенансирование
    'POST api/advance/refinancing' => 'api/advance/refinancing',

    #одобрить рефенансирование
    'POST api/refinancing/<advanceId:\d+>/approved' => 'api/advance/refinancingapproved',

    #выдача займа
    'POST api/refinancing/<advanceId:\d+>/issue-loan' => 'api/advance/refinancingissue-loan',

    #проценты
    'POST api/advance/<advanceId:\d+>/percent' => 'api/advance/percent',

    #история займов
    'GET api/advance/<clientId:\d+>/history' => 'api/advance/history',

    #История платежей последнии 3
    'GET api/profile/<userId:\d+>/paymentlastbyid' => 'api/profile/paymentlastbyid',

    #История платежей
    'GET api/profile/<userId:\d+>/payments' => 'api/profile/paymentsbyid',

    #История платежей текущии
    'GET api/profile/<userId:\d+>/paymentscurrentbyid' => 'api/profile/paymentscurrentbyid',
    
    #Профиль статистика
    'GET api/profile/<userId:\d+>/statisticsbyid' => 'api/profile/statisticsbyid',

    #История заявок
    'GET api/profile/<userId:\d+>/advancedhistory' => 'api/profile/advancedhistorybyid',
    
    #Досрочное погашение расчет
    'POST api/advance/closepercent' => 'api/advance/closepercent',

    #Изменение токена
    'POST api/user/notification/add' => 'api/user/token-add',

    #Включение уведомлений
    'POST api/user/notification/on' => 'api/user/notification-on',
];
