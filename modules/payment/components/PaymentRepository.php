<?php

namespace app\modules\payment\components;

use app\components\BaseRepository;
use app\helpers\DateHelper;
use app\models\Payment;
use app\models\PaymentHistory;
use app\modules\payment\exceptions\PaymentNotFoundException;

class PaymentRepository extends BaseRepository
{

    /**
    * @param int $id
    * @return Payment|array|\yii\db\ActiveRecord
    * @throws PaymentNotFoundException
    */
    public function getPaymentById(int $id)
    {
        $model = Payment::find()
            ->where(['id' => $id])
            ->one();

        if (!$model) {
            throw new PaymentNotFoundException('Payment не найден');
        }

        return $model;
    }

    public function getPaymentByDateAndAdvance(string $date, int $advanceId)
    {
        return Payment::find()
            ->where(['created_at' => $date])
            ->andWhere(['advance_id' => $advanceId])
            ->one();
    }

    public function getNeedPays(string $date, int $userId = null)
    {
        $query = Payment::find();

        if ($userId) {
            $query->andWhere(['user_id' => $userId]);
        }

        return $query
            ->addSelect('*, SUM(amount) as amount')
            ->andWhere(['created_at' => $date])
            ->andWhere(['>', 'amount', 0])
            ->groupBy('client_id')
            ->all();
    }

    public function getPayd(string $date, int $userId = null)
    {
        $query = Payment::find();

        if ($userId) {
            $query->andWhere(['payment.user_id' => $userId]);
        }

        $result = $query
            ->andWhere(['payment.created_at' => $date])
            ->joinWith('paymentHistories')
            ->andWhere(['in', 'payment_history.type', PaymentHistory::getTypePayments()])
            ->groupBy('payment.client_id')
            ->select('payment.*')
            ->addSelect('SUM(payment_history.amount) as amount')
            ->addSelect('SUM(payment.amount) as debt')
            ->all();

        $result = array_filter($result, function($item){
            return $item->debt==0;
        });

        return $result;
    }

    public function getPaysWithClientAndDate(int $clientId, string $date)
    {
        return Payment::find()
            ->where(['client_id' => $clientId])
            ->andWhere(['created_at' => $date])
            ->all();
    }

    public function savePayment(Payment $model)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $model->safeSave();
            $relatedRecords = $model->getRelatedRecords();

            if (isset($relatedRecords['client'])) {
                $model->link('client', $relatedRecords['client']);
            }
            if (isset($relatedRecords['advance'])) {
                $model->link('advance', $relatedRecords['advance']);
            }
            if (isset($relatedRecords['district'])) {
                $model->link('district', $relatedRecords['district']);
            }
            if (isset($relatedRecords['user'])) {
                $model->link('user', $relatedRecords['user']);
            }

            $transaction->commit();
            $model->refresh();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    /**
     * Получение списка долгов, с меткой что часть долга был оплачен сегодня
     */
    public function getLastDebtPayments(int $userId = null)
    {
        $query = Payment::find();

        if (!$userId) {
            $query->andWhere(['payment.user_id' => $userId]);
        }

        $query
            ->where(['>', 'payment.amount', 0])
            ->andWhere(['<>', 'payment.created_at', DateHelper::nowWithoutHours()])
            ->joinWith(['paymentHistories'=>function($query){
                $query->onCondition(['DATE(payment_history.created_at)'=>DateHelper::nowWithoutHours()]);
            }])
            ->select('payment.*')
            ->addSelect(['IFNULL(payment_history.id, 0) as todayPayed'])
            ->groupBy('payment.id')
            ->orderBy('payment.id DESC');

        return $query->all();
    }

    public function getTodayPaymentCount(string $date, int $userId = null)
    {
        $query = PaymentHistory::find();

        if ($userId) {
            $query->andWhere(['payment_history.user_id' => $userId]);
        }

        return $query
            ->andWhere(['DATE(payment_history.created_at)' => $date])
            ->andWhere(['in', 'type', PaymentHistory::getTypePaymentsWithBalance()])
            ->groupBy('type')
            ->select('type, SUM(payment_history.amount) as amount')
            ->all();
    }
}