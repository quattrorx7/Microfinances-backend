<?php

namespace app\modules\advance\components;

use app\models\Advance;
use app\components\BaseFactory;

class AdvanceFactory extends BaseFactory

{
    /**
     * @return Advance
     */
    public function create(): Advance
    {
        $model = new Advance();
        $model->deleted_at = null;
        $model->status = Advance::STATE_SENT;

        return $model;
    }
}