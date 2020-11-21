<?php

namespace app\modules\client\handlers;

use app\modules\client\dto\PayDto;

class StartHandler extends AbstractPayHandler
{
    public function handle(bool $next, PayDto $dto): ?string
    {
        return parent::handle($next, $dto);
    }

}