<?php

/** @var app\components\gii\generators\api\Generator $generator */

echo "<?php\n";
?>

namespace <?= $generator->moduleNamespace?>;

use app\components\BaseRepository;
use app\models\<?= $generator->modelClass?>;
use <?= $generator->exceptionsNamespace?>\<?= $generator->notFoundExceptionClass?>;

class <?= $generator->repositoryClass?> extends BaseRepository
{

    /**
    * @param int $id
    * @return <?= $generator->modelClass?>|array|\yii\db\ActiveRecord
    * @throws <?= $generator->notFoundExceptionClass?>
    */
    public function get<?= $generator->modelClass?>ById(int $id)
    {
        $model = <?= $generator->modelClass?>::find()
            ->where(['id' => $id])
            ->one();

        if (!$model) {
            throw new <?= $generator->notFoundExceptionClass?>('<?= $generator->modelClass?> не найден');
        }

        return $model;
    }

}