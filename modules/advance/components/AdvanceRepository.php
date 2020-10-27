<?php

namespace app\modules\advance\components;

use app\components\BaseRepository;
use app\models\Advance;
use app\modules\advance\exceptions\AdvanceNotFoundException;

class AdvanceRepository extends BaseRepository
{

    /**
    * @param int $id
    * @return Advance|array|\yii\db\ActiveRecord
    * @throws AdvanceNotFoundException    */
    public function getAdvanceById(int $id)
    {
        $model = Advance::find()
            ->where(['id' => $id])
            ->one();

        if (!$model) {
            throw new AdvanceNotFoundException('Advance не найден');
        }

        return $model;
    }

    public function saveAdvance(Advance $advance)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $advance->safeSave();
            $relatedRecords = $advance->getRelatedRecords();

            if (isset($relatedRecords['user'])) {
                $advance->link('user', $relatedRecords['user']);
            }
            if (isset($relatedRecords['client'])) {
                $advance->link('client', $relatedRecords['client']);
            }

            $transaction->commit();
            $advance->refresh();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

}