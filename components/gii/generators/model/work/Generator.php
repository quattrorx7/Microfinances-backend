<?php

namespace app\components\gii\generators\model\work;


use yii\gii\CodeFile;

/**
 * Class Generator
 * @package common\gii\generators\model\work
 */
class Generator extends \yii\gii\Generator
{
    /**
     * Namespace of generated models
     * @var string
     */
    public $ns;

    /**
     * Namespace of base models
     * @var string
     */
    public $baseModelsNs;

    /**
     * Clas short name of generated model
     * @var string
     */
    public $modelClass;

    /**
     * @return string name of the code generator
     */
    public function getName()
    {
        return "Work model generator";
    }

    /**
     * @return CodeFile[] a list of code files to be created.
     */
    public function generate()
    {
        $file = new CodeFile(
            self::getPathOfNamespace($this->ns) . DIRECTORY_SEPARATOR . $this->modelClass . '.php',
            $this->render('model.php', ['generator' => $this])
        );
        return [$file];
    }

    /**
     * Return path of namespace
     * @param $ns
     * @return string
     */
    protected static function getPathOfNamespace($ns)
    {
        if(strpos($ns, '\\') === 0){
            $ns = substr($ns, 1);
        }
        return \Yii::getAlias('@' . str_replace('\\', '/', $ns));
    }
}