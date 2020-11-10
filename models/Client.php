<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * Class Client
 * @package app\models
 *
 * @property-write File $file
 * @property-read mixed $lastDebtPayments
 * @property-read ActiveQuery $activeAdvances
 * @property-read mixed $allDebts
 * @property-read ActiveQuery $files
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

    /**
     * получить все платежи кроме сегодняшнего на которых есть долги
     */
    public function getLastDebtPayments(): ActiveQuery
    {
        return $this
            ->hasMany(Payment::class, ['client_id' => 'id'])
            ->andOnCondition(['>', 'amount', 0]);
    }

    /** общая сумма долга за предыдущие дни */
    public function getAllDebts()
    {
        $payments = $this->lastDebtPayments;
        return array_sum(array_column($payments, 'amount'));
    }
}