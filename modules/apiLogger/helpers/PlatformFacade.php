<?php

namespace app\modules\apiLogger\helpers;

use app\modules\apiLogger\components\constants\PlatformConst;

class PlatformFacade
{
    public static function getPlatforms(): array
    {
        return [
            PlatformConst::PLATFORM_ANDROID => PlatformConst::PLATFORM_ANDROID,
            PlatformConst::PLATFORM_IOS => PlatformConst::PLATFORM_IOS,
            PlatformConst::PLATFORM_WEB => PlatformConst::PLATFORM_WEB
        ];
    }
}