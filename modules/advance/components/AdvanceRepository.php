<?php

namespace app\modules\advance\components;

use app\components\BaseRepository;
use app\helpers\DateHelper;
use app\models\Advance;
use app\modules\advance\exceptions\AdvanceNotFoundException;
use yii\db\ActiveQuery;

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

    public function getActiveAdvances(string $date)
    {
        return Advance::find()
            ->andWhere(['IS', 'deleted_at', null])
            ->andWhere(['payment_status' => Advance::PAYMENT_STATUS_STARTED])
            ->andWhere(['>', 'summa_left_to_pay', 0])
            ->andWhere(['<', 'issue_date', $date . ' 06:00:00'])
            ->andWhere(['>', 'payment_left', 0])
            ->all();
    }

    public function getDebtAdvances(string $date)
    {
        return Advance::find()
            ->andWhere(['IS', 'deleted_at', null])
            ->andWhere(['payment_status' => Advance::PAYMENT_STATUS_STARTED])
            ->andWhere(['>', 'summa_left_to_pay', 0])
            ->andWhere(['<', 'issue_date', $date . ' 06:00:00'])
            ->all();
    }

    public function createSearchQuery(): ActiveQuery
    {
        return Advance::find()
            ->andWhere(['IS', 'deleted_at', null])
            ->andWhere(['payment_status' => Advance::PAYMENT_STATUS_CLOSED]);
    }

    public function createDebtQuery(): ActiveQuery
    {
        return Advance::find()
            ->select(['advance.*'])
            ->andWhere(['IS', 'deleted_at', null])
            ->andWhere(['payment_status' => Advance::PAYMENT_STATUS_STARTED])
            ->andWhere(['payment_left' => 0])
            ->andWhere(['>', 'summa_left_to_pay', 0])
            ->andWhere(['<', 'end_date', DateHelper::nowWithoutHours()]);
    }

    public function getNewQuery(string $date, $userId=null): ActiveQuery
    {
        $query = Advance::find()
            ->andWhere(['created_at' => $date]);

        if($userId)
            $query->andWhere(['user_id' => $userId]);

        return $query;
    }

    public function getHistoryByClientId(int $clientId): array
    {
        return Advance::find()
                ->where(['client_id' => $clientId])
                ->orderBy(['id' => SORT_DESC])
                ->andwhere(['not in','status', array(Advance::STATE_SENT, Advance::STATE_APPROVED)])
                ->all();
    }

    public function getHistoryAppByUserId(int $userId)
    {
        return Advance::find()
                ->where(['user_id' => $userId])
                ->orderBy(['id' => SORT_DESC])
                ->andwhere(['>=','created_at', DateHelper::getModifyDate(DateHelper::now(), '-7 day')])
                ->all();
    }

    public function getStatistic($from, $to): ActiveQuery
    {
        $query = Advance::find()
            ->andWhere(['status' => Advance::STATE_ISSUED]);

        if($from){
            $query->andWhere(['>=', 'DATE(issue_date)', $from]);
        }
        if($to){
            $query->andWhere(['<=', 'DATE(issue_date)', $to]);
        }

        return $query;
    }
}