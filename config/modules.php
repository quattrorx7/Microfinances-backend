<?php

use app\modules\admin\AdminModule;
use app\modules\api\ApiModule;
use app\modules\apiLogger\ApiLoggerModule;

return [
    'api'   => [
        'class' => ApiModule::class
    ],
    'admin' => [
        'class'  => AdminModule::class,
        'layout' => '@app/modules/admin/views/layouts/main'
    ],
    'apiLogger'  => [
        'class' => ApiLoggerModule::class,
        'layout' => '@app/modules/admin/views/layouts/main'
    ],
];
