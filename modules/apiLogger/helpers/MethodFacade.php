<?php

namespace app\modules\apiLogger\helpers;

use app\modules\apiLogger\components\constants\MethodConst;

class MethodFacade
{
    public static function getMethods(): array
    {
        return [
            MethodConst::METHOD_GET => MethodConst::METHOD_GET,
            MethodConst::METHOD_POST => MethodConst::METHOD_POST,
            MethodConst::METHOD_DELETE => MethodConst::METHOD_DELETE,
            MethodConst::METHOD_PUT => MethodConst::METHOD_PUT
        ];
    }
}