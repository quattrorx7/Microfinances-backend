<?php

namespace app\components\gii\generators\mod;

use yii\gii\CodeFile;

/**
 * Class Generator
 * @package app\components\gii\generators\module
 */
class Generator extends \yii\gii\Generator
{

    public $modelClass;

    public $moduleNamespace;

    public $modelLowerCamelCase;

    public $repositoryClass;

    public $factoryClass;

    public $serviceClass;

    /**
     * @return string name of the code generator
     */
    public function getName()
    {
        return 'Module generator';
    }

    /**
     * @return array|CodeFile[]
     * @throws \yii\base\Exception
     */
    public function generate()
    {
        $modulePath = 'modules/'.$this->modelLowerCamelCase.'/components';

        return [
            new CodeFile($modulePath . '/'.$this->factoryClass.'.php', $this->render('factory.php', ['generator' => $this])),
            new CodeFile($modulePath . '/'.$this->repositoryClass.'.php', $this->render('repository.php', ['generator' => $this])),
            new CodeFile($modulePath . '/'.$this->serviceClass.'.php', $this->render('service.php', ['generator' => $this]))
        ];
    }
}