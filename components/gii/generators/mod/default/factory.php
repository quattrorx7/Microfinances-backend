<?php

/** @var app\components\gii\generators\mod\Generator $generator */

echo "<?php\n";
?>

namespace <?= $generator->moduleNamespace?>;

use app\models\<?= $generator->modelClass?>;

class <?= $generator->factoryClass?>

{
    /**
     * @return <?= $generator->modelClass?>

     */
    public function create(): <?= $generator->modelClass?>

    {
        return new <?= $generator->modelClass?>();
    }
}