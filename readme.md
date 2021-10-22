## Требования
 - PHP 7.4 и выше
 - RabbitMQ для логирования

## Настройки
 - config/db.php
 - config/web.php

 Для Windows установить "false"
    `'linkAssets' => true,`

 - если не устанавливать RabbitMQ, то закомментировать 'Yii::$app->apiloggerQueue->push...' в файлах:
   `modules/apiLogger/services/DbLoggerService.php
    modules/apiLogger/services/FileLoggerService.php`

## Запуск
 - composer install
 - php yii migrate
 - php yii generator/models
 - php yii user/create - создание пользователя

## Cron
 - payment/create на 6:00
 - payment/debts на 5:45
 ```
0 6  * * * /usr/bin/php /var/www/micro/yii payment/create >> /var/www/micro/cron_log.txt  2>&1
45 5  * * * /usr/bin/php /var/www/micro/yii payment/debts >> /var/www/micro/cron_log.txt  2>&1
 ```
