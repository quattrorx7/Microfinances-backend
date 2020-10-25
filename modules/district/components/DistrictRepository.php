<?php

namespace app\modules\district\components;

use app\components\BaseRepository;
use app\models\District;
use app\modules\district\exceptions\DistrictNotFoundException;

class DistrictRepository extends BaseRepository
{

    /**
    * @param int $id
    * @return District|array|\yii\db\ActiveRecord
    * @throws DistrictNotFoundException    */
    public function getDistrictById(int $id)
    {
        $model = District::find()
            ->where(['id' => $id])
            ->one();

        if (!$model) {
            throw new DistrictNotFoundException('District не найден');
        }

        return $model;
    }

}