<?php

namespace app\modules\files\repository;

use app\helpers\DateHelper;
use app\models\File;
use app\modules\files\helpers\FilesHelper;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

class FilesRepository
{
    /**
     * @param File $file
     * @return File
     */
    public function saveFile($file)
    {
        $file->save();
        return $file;
    }

    /**
     * @param $id
     * @param false $unlink
     * @return bool
     * @throws Exception
     */
    public function removeById($id, $unlink = false)
    {
        $file = File::findOne($id);

        if ($file) {
            return $this->remove($file, $unlink);
        }

        throw new Exception('File ' . $id . ' does not exists');
    }

    /**
     * @param File $file
     * @param bool $unlink
     * @return bool
     * @throws \Exception
     */
    public function remove($file, $unlink = false)
    {
        $file->deleted_at = DateHelper::now();

        if ($file->save(false)) {
            $path = $file->getLocalPath();

            if ($unlink && $file->type === FilesHelper::TYPE_LOCAL && file_exists($path)) {
                unlink($path);
            }

            return true;
        }

        return false;
    }

    public function getById($id)
    {
        $model = File::find()
            ->where(['id' => $id])
            ->one();

        if (!$model) {
            throw new NotFoundHttpException("Файл с id=$id не найден");
        }

        return $model;
    }

    public function getByHashAndId(string $hash, int $id): File
    {
        $model = File::find()
            ->where(['hash' => $hash])
            ->andWhere(['id' => $id])
            ->one();

        if (!$model) {
            throw new NotFoundHttpException('Файл не найден');
        }

        return $model;
    }

    public function getBy($condition)
    {
        return File::find()
            ->where($condition)
            ->all();
    }
}