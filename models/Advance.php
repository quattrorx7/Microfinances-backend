<?php

namespace app\models;

/**
 * Class Advance
 * @package app\models
 */
class Advance extends \app\models\base\Advance
{
    public CONST DAYS_15 = 15;
    public CONST DAYS_30 = 30;
    public CONST DAYS_45 = 45;
    public CONST DAYS_60 = 60;

    public CONST STATE_SENT = 'sent';
    public CONST STATE_DENIED = 'denied';
    public CONST STATE_APPROVED = 'approved';
    public CONST STATE_ISSUED = 'issued';

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
}