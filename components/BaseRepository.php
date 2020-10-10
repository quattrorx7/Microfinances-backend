<?php

namespace app\components;

use app\components\yii\BaseAR;
use yii\base\BaseObject;

class BaseRepository extends BaseObject
{

    /**
     * @param BaseAR $model
     * @throws exceptions\UnSuccessModelException
     */
    public function save(BaseAR $model): void
    {
        $model->safeSave();
        $model->refresh();
    }

}