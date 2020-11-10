<?php

namespace app\models;

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
    }
}