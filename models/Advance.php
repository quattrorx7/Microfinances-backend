<?php

namespace app\models;

use app\helpers\DateHelper;

/**
 * Class Advance
 * @package app\models
 */
class Advance extends \app\models\base\Advance
{
    public CONST STATE_SENT = 'sent';
    public CONST STATE_DENIED = 'denied';
    public CONST STATE_APPROVED = 'approved';
    public CONST STATE_ISSUED = 'issued';

    public CONST PAYMENT_STATUS_NULL = 0;
    public CONST PAYMENT_STATUS_STARTED = 4;
    public CONST PAYMENT_STATUS_CLOSED = 8;

    public $debt;
    public $todayPayed;

    public function afterSave($insert, $changedAttributes)
    {
        if(isset($changedAttributes['user_id'])){
            self::updateAll(['user_id'=>$this->user_id], ['client_id'=>$this->client_id]);
            Payment::updateAll(['user_id'=>$this->user_id], ['client_id'=>$this->client_id]);
        }
        if(isset($changedAttributes['district_id'])){
            Payment::updateAll(['district_id'=>$this->district_id], ['client_id'=>$this->id]);
        }
    }

    /** одобрено */
    public function isApproved(): bool
    {
        return $this->status === self::STATE_APPROVED;
    }

    /** отправлено */
    public function isSent(): bool
    {
        return $this->status === self::STATE_SENT;
    }

    /** отказано */
    public function isDenied(): bool
    {
        return $this->status === self::STATE_DENIED;
    }

    public function isOwner(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function hasNote(): bool
    {
        return $this->note_id !== null;
    }

    public function setNote(File $file): void
    {
        $this->populateRelation('note', $file);
    }

    public function activatePaymentProcess(): void
    {
        $this->payment_status = self::PAYMENT_STATUS_STARTED;
        $this->summa_left_to_pay = $this->summa_with_percent;
        $this->payment_left = $this->limitation;

        $this->issue_date = DateHelper::formatDate($this->issue_date, 'Y-m-d') . ' ' . DateHelper::formatDate(DateHelper::now(), 'H:i:s');
        $this->end_date = DateHelper::formatDate(DateHelper::getModifyDate($this->issue_date, '+'.($this->payment_left-1).' day'), 'Y-m-d');
    }
}