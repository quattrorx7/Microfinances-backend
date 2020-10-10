<?php

namespace app\components\exceptions;

use yii\base\Model;

class UnSuccessModelException extends UnSuccessException
{
    /**
     * UnSuccessException constructor.
     *
     * @param $message
     * @param Model|null $model
     */
    public function __construct($message, Model $model = null)
    {
        $this->errors = $model && $model->hasErrors() ? $this->serializeModelErrors($model) : [];

        parent::__construct($message, $this->errors);
    }

    /**
     * @param Model $model
     *
     * @return array
     */
    protected function serializeModelErrors(Model $model): array
    {
        $result = [];

        foreach ($model->getFirstErrors() as $name => $message) {
            $result[$name] = $message;
        }

        return $result;
    }
}
