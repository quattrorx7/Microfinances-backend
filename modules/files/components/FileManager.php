<?php

namespace app\modules\files\components;

use Yii;
use yii\base\Component;

class FileManager extends Component
{
    private $_file;

    public static function create($path, $file): FileManager
    {
        Yii::$app->fs->write($path, file_get_contents($file));
        $manager = new self;
        $manager->_file = $path;
        return $manager;
    }

    public function getFile()
    {
        return $this->_file;
    }

    public function rename($new)
    {
        return Yii::$app->fs->rename($this->_file, $new);
    }

    public function getMimeType()
    {
        return Yii::$app->fs->getMimetype($this->_file);
    }

    public function getFileSize()
    {
        return Yii::$app->fs->getSize($this->_file);
    }
}