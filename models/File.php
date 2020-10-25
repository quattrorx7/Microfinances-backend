<?php

namespace app\models;

use app\modules\files\helpers\BaseHelper;
use app\modules\files\helpers\FilesHelper;
use app\modules\files\helpers\StringHelper;

/**
 * Class File
 * @package app\models
 *
 * @property-read string $localPath
 */
class File extends \app\models\base\File
{
    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (empty($this->type)) {
            $this->type = FilesHelper::TYPE_LOCAL;
        }

        $this->full_path = $this->fullPath();

        return parent::beforeSave($insert);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return rtrim($this->path, '/') . '/' . $this->filename;
    }

    /**
     * @return string
     */
    public function fullPath()
    {
        if ($this->type === FilesHelper::TYPE_LOCAL) {
            $hostUrl = StringHelper::getHostUrl();
            $base = !empty($hostUrl) ? $hostUrl : BaseHelper::webPath();

            return $base . '/' . FilesHelper::FILES_DIR . '/' . $this->getPath();
        }

        return $this->getPath();
    }

    /**
     * @return string
     */
    public function getLocalPath()
    {
        return BaseHelper::webPath() . '/' . FilesHelper::FILES_DIR . '/' . $this->getPath();
    }
}