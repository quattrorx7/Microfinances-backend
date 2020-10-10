<?php

namespace app\components\exceptions;

use yii\web\HttpException;

class UnSuccessException extends HttpException
{
    /**
     * @var array
     */
    protected array $errors;

    /**
     * UnSuccessException constructor.
     *
     * @param $message
     * @param array $errors ['key' => 'message']
     */
    public function __construct($message, array $errors = [])
    {
        $this->errors = $errors;
        $message = $this->getFirstError() ?? $message;
        parent::__construct(422, $message, 0, null);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return string|null
     */
    protected function getFirstError(): ?string
    {
        if (empty($this->getErrors())) {
            return null;
        }

        $firstKey = array_key_first($this->getErrors());

        return $this->getErrors()[$firstKey];
    }
}
