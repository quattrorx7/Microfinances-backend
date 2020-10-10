<?php

/** @var app\components\gii\generators\api\Generator $generator */

echo "<?php\n";
?>

namespace <?= $generator->serializerNamespace?>;

use app\models\<?= $generator->modelClass?>;
use app\components\serializers\AbstractProperties;

class <?= $generator->serializerClass?> extends AbstractProperties

{

    public function getProperties(): array
    {
        return [
            <?= $generator->modelClass?>::class => [

            ]
        ];
    }

}