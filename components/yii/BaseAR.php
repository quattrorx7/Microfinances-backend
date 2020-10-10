<?php

namespace app\components\yii;

use app\components\exceptions\UnSuccessModelException;
use yii\db\ActiveRecord;

class BaseAR extends ActiveRecord
{
    /**
     * @param null $attributeNames
     *
     * @return bool
     * @throws UnSuccessModelException
     */
    public function safeSave($attributeNames = null): bool
    {
        $result = $this->save(true, $attributeNames);

        if ($this->hasErrors()) {
            throw new UnSuccessModelException($this->getErrorSummary(true)[0], $this);
        }

        return $result;
    }
}
