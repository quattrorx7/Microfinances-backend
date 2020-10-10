<?php

namespace app\components\gii\generators\api;

use yii\gii\CodeFile;

/**
 * Class Generator
 * @package app\components\gii\generators\module
 *
 * @property-read string $name
 */
class Generator extends \yii\gii\Generator
{

    public $modelClass;

    public $moduleNamespace;

    public $apiNamespace;

    public $exceptionsNamespace;

    public $formsNamespace;

    public $serializerNamespace;

    public $providersNamespace;

    public $modelLowerCamelCase;

    public $createExceptionClass;

    public $updateExceptionClass;

    public $notFoundExceptionClass;

    public $createFormClass;

    public $updateFormClass;

    public $providerClass;

    public $repositoryClass;

    public $factoryClass;

    public $serviceClass;

    public $populatorClass;

    public $apiControllerClass;

    public $serializerClass;

    /**
     * @return string name of the code generator
     */
    public function getName()
    {
        return 'Api generator';
    }

    /**
     * @return array|CodeFile[]
     * @throws \yii\base\Exception
     */
    public function generate()
    {
        $exceptionsPath = 'modules/'.$this->modelLowerCamelCase.'/exceptions';

        $modulePath = 'modules/'.$this->modelLowerCamelCase.'/components';
        $eventsPath = 'modules/'.$this->modelLowerCamelCase.'/events';
        $formsPath = 'modules/'.$this->modelLowerCamelCase.'/forms';
        $providersPath = 'modules/'.$this->modelLowerCamelCase.'/providers';
        $apiControllerPath = 'modules/api/controllers';
        $serializerPath = 'modules/api/serializer/'.$this->modelLowerCamelCase;

        $files = [];


        if (!file_exists(\Yii::getAlias($exceptionsPath . '/'.$this->createExceptionClass.'.php'))) {
            $files[] = new CodeFile($exceptionsPath . '/'.$this->createExceptionClass.'.php', $this->render('exceptions/create.php', ['generator' => $this]));
        }
        if (!file_exists(\Yii::getAlias($exceptionsPath . '/'.$this->updateExceptionClass.'.php'))) {
            $files[] = new CodeFile($exceptionsPath . '/'.$this->updateExceptionClass.'.php', $this->render('exceptions/update.php', ['generator' => $this]));
        }
        if (!file_exists(\Yii::getAlias($exceptionsPath . '/'.$this->notFoundExceptionClass.'.php'))) {
            $files[] = new CodeFile($exceptionsPath . '/'.$this->notFoundExceptionClass.'.php', $this->render('exceptions/notFound.php', ['generator' => $this]));
        }
        if (!file_exists(\Yii::getAlias($formsPath . '/'.$this->createFormClass.'.php'))) {
            $files[] = new CodeFile($formsPath . '/'.$this->createFormClass.'.php', $this->render('forms/create.php', ['generator' => $this]));
        }
        if (!file_exists(\Yii::getAlias($formsPath . '/'.$this->updateFormClass.'.php'))) {
            $files[] = new CodeFile($formsPath . '/'.$this->updateFormClass.'.php', $this->render('forms/update.php', ['generator' => $this]));
        }

        if (!file_exists(\Yii::getAlias($providersPath . '/'.$this->providerClass.'.php'))) {
            $files[] = new CodeFile($providersPath . '/'.$this->providerClass.'.php', $this->render('provider/index.php', ['generator' => $this]));
        }

        if (!file_exists(\Yii::getAlias($modulePath . '/'.$this->factoryClass.'.php'))) {
            $files[] = new CodeFile($modulePath . '/'.$this->factoryClass.'.php', $this->render('factory.php', ['generator' => $this]));
        }
        if (!file_exists(\Yii::getAlias($modulePath . '/'.$this->repositoryClass.'.php'))) {
            $files[] = new CodeFile($modulePath . '/'.$this->repositoryClass.'.php', $this->render('repository.php', ['generator' => $this]));
        }
        if (!file_exists(\Yii::getAlias($modulePath . '/'.$this->populatorClass.'.php'))) {
            $files[] = new CodeFile($modulePath . '/'.$this->populatorClass.'.php', $this->render('populator.php', ['generator' => $this]));
        }
        if (!file_exists(\Yii::getAlias($modulePath . '/'.$this->serviceClass.'.php'))) {
            $files[] = new CodeFile($modulePath . '/'.$this->serviceClass.'.php', $this->render('service.php', ['generator' => $this]));
        }
        if (!file_exists(\Yii::getAlias($serializerPath . '/'.$this->serializerClass.'.php'))) {
            $files[] = new CodeFile($serializerPath . '/'.$this->serializerClass.'.php', $this->render('serializer.php', ['generator' => $this]));
        }
        if (!file_exists(\Yii::getAlias($apiControllerPath . '/'.$this->apiControllerClass.'.php'))) {
            $files[] = new CodeFile($apiControllerPath . '/'.$this->apiControllerClass.'.php', $this->render('controller.php', ['generator' => $this]));
        }

        return $files;
    }
}