<?php

namespace app\modules\client\handlers;

use app\modules\client\dto\PayDto;

interface PayHandler
{
    public function setNext(PayHandler $handler): PayHandler;

    public function handle(bool $next, PayDto $dto): ?string;

}