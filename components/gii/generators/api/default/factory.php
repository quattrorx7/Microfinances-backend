<?php

/** @var app\components\gii\generators\api\Generator $generator */

echo "<?php\n";
?>

namespace <?= $generator->moduleNamespace?>;

use app\models\<?= $generator->modelClass?>;
use app\components\BaseFactory;

class <?= $generator->factoryClass?> extends BaseFactory

{
    /**
     * @return <?= $generator->modelClass?>

     */
    public function create(): <?= $generator->modelClass?>

    {
        return new <?= $generator->modelClass?>();
    }
}