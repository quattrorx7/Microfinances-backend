<?php

namespace app\models;

use app\helpers\DateHelper;
use yii\db\ActiveQuery;

/**
 * Class Client
 * @package app\models
 *
 * @property-write File $file
 * @property-read mixed $lastDebtPayments
 * @property-read mixed $activeAdvances
 * @property-read mixed $allDebts
 * @property-read mixed $activePayments
 * @property-read mixed $files
 */
class Client extends \app\models\base\Client
{

    public function getFiles(): ActiveQuery
    {
        return $this->hasMany(File::class, ['id' => 'file_id'])
            ->via('clientFiles');
    }

    public function setFile(array $files): void
    {
        $this->populateRelation('files', $files);
    }

    public function getActiveAdvances(): ActiveQuery
    {
        return $this
            ->hasMany(Advance::class, ['client_id' => 'id'])
            ->andOnCondition(['IS', 'deleted_at', null])
            ->andOnCondition(['status' => Advance::STATE_ISSUED]);
    }

    public function getActivePayments(): ActiveQuery
    {
        return $this
            ->hasMany(Payment::class, ['client_id' => 'id'])
            ->andOnCondition(['created_at' => DateHelper::nowWithoutHours()])
            ->andOnCondition(['<>', 'amount', 0]);
    }

    /**
     * получить все платежи кроме сегодняшнего на которых есть долги
     */
    public function getLastDebtPayments(): ActiveQuery
    {
        return $this
            ->hasMany(Payment::class, ['client_id' => 'id'])
            ->andOnCondition(['>', 'amount', 0])
            ->andOnCondition(['<>', 'created_at', DateHelper::nowWithoutHours()]);
    }

    /** общая сумма долга за предыдущие дни */
    public function getAllDebts()
    {
        $payments = $this->lastDebtPayments;
        return array_sum(array_column($payments, 'amount'));
    }

    /**
     * получить все платежи, которые нужно заплатить(долги + активные платежи)
     */
    public function getActiveandAndDebtPayments(): ActiveQuery
    {
        return $this
            ->hasMany(Payment::class, ['client_id' => 'id'])
            ->andOnCondition(['>', 'amount', 0]);
    }

    /**
     * Общая сумма всех платежей, которые нужно заплатить(долги + активные платежи)
     */
    public function getActiveandAndDebtPaymentsSum():int
    {
        return (int)$this->getActiveandAndDebtPayments()
            ->sum('amount');
    }
}