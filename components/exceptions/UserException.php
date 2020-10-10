<?php

namespace app\components\exceptions;

use Exception;
use yii\web\HttpException;

class UserException extends HttpException
{
    /**
     * UserException constructor.
     * Use to write error code 422 and not 500
     *
     * @param $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct(422, $message, $code, $previous);
    }
}
