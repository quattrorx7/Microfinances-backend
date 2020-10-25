<?php

namespace app\modules\api\serializer\files;

use app\components\serializers\AbstractProperties;
use app\models\File;

class FilesSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            File::class => [
                'id',
                'full_path',
                'deleted_at'
            ]
        ];
    }

}