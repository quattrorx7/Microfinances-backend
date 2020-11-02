<?php

namespace app\modules\advance\components;

use app\components\BaseRepository;
use app\models\Advance;
use app\modules\advance\exceptions\AdvanceNotFoundException;

/**
 *
 * @property-read mixed $lastAdvances
 */
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

    public function getLastAdvances()
    {
        return Advance::find()
            ->andWhere(['IS', 'deleted_at', null])
            ->orderBy(['id' => SORT_DESC])
            ->all();
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

    public function saveAdvanceNote(Advance $advance)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $advance->safeSave();
            $relatedRecords = $advance->getRelatedRecords();

            if (isset($relatedRecords['note'])) {
                $advance->link('note', $relatedRecords['note']);
            }

            $transaction->commit();
            $advance->refresh();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

}