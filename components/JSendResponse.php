<?php

namespace app\components;

use yii\helpers\Json;

class JSendResponse
{
    /**
     * response status
     *
     * @var bool
     */
    public bool $success;

    /**
     * success or error message
     *
     * @var string
     */
    public ?string $message;

    /**
     * @var array|null
     */
    public ?array $data;

    /**
     * @var array|null
     */
    public ?array $errors;

    public function __construct($success, $message, array $data = null, array $errors = null)
    {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
        $this->errors = $errors;
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
            'errors' => $this->errors,
        ];
    }

    /**
     * @return string
     */
    public function getJson(): string
    {
        return Json::encode($this->toArray());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getJson();
    }

    /**
     * @param $message
     * @param array|null $data
     * @param array|null $errors
     *
     * @return JSendResponse
     */
    public static function error($message, array $data = null, array $errors = null): JSendResponse
    {
        return new self(false, $message, $data, $errors);
    }

    /**
     * @param $message
     * @param array|null $data
     *
     * @return JSendResponse
     */
    public static function success($message, array $data = null): JSendResponse
    {
        return new self(true, $message, $data);
    }
}
