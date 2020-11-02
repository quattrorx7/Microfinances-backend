<?php

namespace app\modules\advance\components;

use app\helpers\DateHelper;
use app\models\Advance;
use app\components\BaseFactory;
use Exception;

class AdvanceFactory extends BaseFactory

{
    /**
     * @return Advance
     * @throws Exception
     */
    public function create(): Advance
    {
        $model = new Advance();
        $model->deleted_at = null;
        $model->created_at = DateHelper::now();
        $model->status = Advance::STATE_SENT;

        return $model;
    }

    /**
     * @return Advance
     * @throws Exception
     */
    public function createWithAdmin(): Advance
    {
        $model = new Advance();
        $model->deleted_at = null;
        $model->created_at = DateHelper::now();
        $model->status = Advance::STATE_APPROVED;

        return $model;
    }
}