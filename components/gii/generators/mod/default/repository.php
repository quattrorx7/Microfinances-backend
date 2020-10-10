<?php

/** @var app\components\gii\generators\mod\Generator $generator */

echo "<?php\n";
?>

namespace <?= $generator->moduleNamespace?>;

use app\components\exceptions\UnSuccessModelException;
use app\models\<?= $generator->modelClass?>;

class <?= $generator->repositoryClass?>

{
    /**
    * @param <?= $generator->modelClass?> $model
    * @throws UnSuccessModelException
    */
    public function save(<?= $generator->modelClass?> $model): void
    {
        $model->safeSave();
        $model->refresh();
    }
}