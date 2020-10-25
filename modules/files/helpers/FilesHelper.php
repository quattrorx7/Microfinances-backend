<?php

namespace app\modules\files\helpers;

use Yii;
use yii\base\Exception;

class FilesHelper
{
    public const PATH_DEPTH              = 2;
    public const PATH_DIR_LETTERS_LENGTH = 2;
    public const FILENAME_LENGTH         = 6;

    public const FILES_DIR = 'files';
    public const TEMP_DIR  = 'temp';

    public const TYPE_LOCAL  = 'local';
    public const TYPE_AMAZON = 'amazon';

    /**
     * @param string $fileName
     * @return string
     */
    public static function getHash($fileName)
    {
        return md5_file($fileName);
    }

    /**
     * @param string $hash
     * @return string
     */
    public static function setPath($hash)
    {
        $path = '';

        for ($i = 0; $i < self::PATH_DEPTH; $i++) {
            $path .= substr($hash, $i, self::PATH_DIR_LETTERS_LENGTH) . '/';
        }

        return $path;
    }

    /**
     * @param string $file
     * @return string
     * @throws Exception
     */
    public static function setFileName($file)
    {
        $file = str_replace(' ', '', $file);

        return md5(pathinfo($file, PATHINFO_FILENAME)) . '_' .
            self::generateFileName() . '.' . pathinfo($file, PATHINFO_EXTENSION);
    }

    /**
     * @param null $title
     * @return string
     */
    public static function tempFilePath($title = null)
    {
        if (empty($title)) {
            $title = uniqid('', true);
        }

        return BaseHelper::webPath() . '/' . self::FILES_DIR . '/' . self::TEMP_DIR . '/' . $title;
    }

    /**
     * @param int $length
     * @return string
     * @throws Exception
     */
    public static function generateFileName($length = self::FILENAME_LENGTH)
    {
        return Yii::$app->security->generateRandomString($length);
    }

    public static function createDirectory($directory): void
    {
        $directory = Yii::getAlias($directory);

        if (!file_exists($directory)) {
            if (!mkdir($directory) && !is_dir($directory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
            }
        }
    }
}