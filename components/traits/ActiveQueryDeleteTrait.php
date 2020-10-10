<?php

namespace app\components\traits;

trait ActiveQueryDeleteTrait
{
    /**
     * Показывать только удаленные
     * @return mixed
     */
    public function deleted()
    {
        return $this->andOnCondition(['not', [(new $this->modelClass)->tableName() . '.deleted_at'=> null]]);
    }

    /**
     * Показывать только активные
     * @return mixed
     */
    public function active()
    {
        return $this->andOnCondition([(new $this->modelClass)->tableName() . '.deleted_at' => null]);
    }

}