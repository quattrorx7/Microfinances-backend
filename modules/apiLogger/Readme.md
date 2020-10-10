# Подключение

Для просмотра админки, указать модуль в конфиг-файле
```php
'apiLogger'  => [
        'class' => ApiLoggerModule::class,
        'layout' => '@app/modules/admin/views/layouts/main'
    ],
```

# Заголовки
Для обозначения платформы используется заголовок ```App-Platform```

Для обозначения версии приложения используется заголовок ```App-Version```

Указанные аголовки нужно присылать в каждом запросе

# Логирование в базу данных

Для требуемого модуля, указать в init-методе, по урлу `'/apiLogger/default/index'` - админка

```php
$serviceFactory = new DbLoggerCreator();
$apiLoggerService = $serviceFactory->createService();

$this->on(Application::EVENT_BEFORE_ACTION, static function () use ($apiLoggerService) {
    $apiLoggerService->exportRequest(Yii::$app->request);
});

$this->on(Application::EVENT_AFTER_ACTION, static function ($event) use ($apiLoggerService) {
    $apiLoggerService->exportResponse(
        $event->result,
        Yii::$app->user->id
    );
});

Yii::$app->on(ErrorHandler::EVENT_AFTER_API_ERROR_HANDLER, static function (ErrorHandlerEvent $event) use ($apiLoggerService) {
    $apiLoggerService->exportResponse(
        $event->result,
        Yii::$app->user->id
    );
});
```

# Логирование в файлы

Для требуемого модуля, указать в init-методе, по урлу `'/apiLogger/show-log/index'` - админка

```php
$serviceFactory = new FileLoggerCreator();
$apiLoggerService = $serviceFactory->createService();

$this->on(Application::EVENT_BEFORE_ACTION, static function () use ($apiLoggerService) {
    $apiLoggerService->exportRequest(Yii::$app->request);
});

$this->on(Application::EVENT_AFTER_ACTION, static function ($event) use ($apiLoggerService) {
    $apiLoggerService->exportResponse(
        $event->result,
        Yii::$app->user->id
    );
});

Yii::$app->on(ErrorHandler::EVENT_AFTER_API_ERROR_HANDLER, static function (ErrorHandlerEvent $event) use ($apiLoggerService) {
    $apiLoggerService->exportResponse(
        $event->result,
        Yii::$app->user->id
    );
});
```