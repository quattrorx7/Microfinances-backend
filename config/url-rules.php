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
];
