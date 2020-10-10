<?php

namespace app\modules\apiLogger;

/**
 * apiLogger module definition class
 */
class ApiLoggerModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\apiLogger\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        \Yii::configure($this, require __DIR__ . '/apilogger-config.php');
    }
}
