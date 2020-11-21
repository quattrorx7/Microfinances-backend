<?php

namespace app\modules\client\handlers;

use app\modules\client\dto\PayDto;

class AbstractPayHandler implements PayHandler
{
    private $nextHandler;

    public function setNext(PayHandler $handler): PayHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(bool $next, PayDto $dto): ?string
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($next, $dto);
        }

        return null;
    }

}