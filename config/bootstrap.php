<?php

use app\components\yii\Container;

Yii::$classMap[Container::class] = __DIR__.'/../components/yii/Container.php';

Yii::setAlias('@static', dirname(__DIR__) . '/web/static');
// DI
Yii::$container = new app\components\yii\Container();


