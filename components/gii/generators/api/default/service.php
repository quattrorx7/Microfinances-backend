<?php

/** @var app\components\gii\generators\api\Generator $generator */

echo "<?php\n";
?>

namespace <?= $generator->moduleNamespace?>;

use app\components\BaseService;
use app\components\exceptions\UnSuccessModelException;
use app\models\<?= $generator->modelClass?>;
use <?= $generator->exceptionsNamespace?>\<?= $generator->notFoundExceptionClass?>;
use <?= $generator->formsNamespace?>\<?= $generator->createFormClass?>;
use <?= $generator->formsNamespace?>\<?= $generator->updateFormClass?>;
use yii\db\StaleObjectException;

class <?= $generator->serviceClass?> extends BaseService
{

    protected <?= $generator->factoryClass?> $<?= $generator->modelLowerCamelCase?>Factory;

    protected <?= $generator->repositoryClass?> $<?= $generator->modelLowerCamelCase?>Repository;

    protected <?= $generator->populatorClass?> $<?= $generator->modelLowerCamelCase?>Populator;

    public function injectDependencies(<?= $generator->factoryClass?> $<?= $generator->modelLowerCamelCase?>Factory, <?= $generator->repositoryClass?> $<?= $generator->modelLowerCamelCase?>Repository, <?= $generator->populatorClass ?> $<?= $generator->modelLowerCamelCase?>Populator): void
    {
        $this-><?= $generator->modelLowerCamelCase?>Factory = $<?= $generator->modelLowerCamelCase?>Factory;
        $this-><?= $generator->modelLowerCamelCase?>Repository = $<?= $generator->modelLowerCamelCase?>Repository;
        $this-><?= $generator->modelLowerCamelCase?>Populator = $<?= $generator->modelLowerCamelCase?>Populator;
    }

    /**
    * @param <?= $generator->createFormClass?> $form
    * @return <?= $generator->modelClass?>

    * @throws UnSuccessModelException
    */
    public function createByForm(<?= $generator->createFormClass?> $form): <?= $generator->modelClass?>

    {
        $model = $this-><?= $generator->modelLowerCamelCase?>Factory->create();
        $this-><?= $generator->modelLowerCamelCase?>Populator
            ->populateFromCreateForm($model, $form);

        $this-><?= $generator->modelLowerCamelCase?>Repository->save($model);

        return $model;
    }

    /**
    * @param <?= $generator->modelClass?> $model
    * @param <?= $generator->updateFormClass?> $form
    * @return <?= $generator->modelClass?>

    * @throws UnSuccessModelException
    */
    public function updateByForm(<?= $generator->modelClass?> $model, <?= $generator->updateFormClass?> $form): <?= $generator->modelClass?>

    {
        $this-><?= $generator->modelLowerCamelCase?>Populator
            ->populateFromUpdateForm($model, $form);

        $this-><?= $generator->modelLowerCamelCase?>Repository->save($model);

        return $model;
    }

    /**
    * @param $id
    * @return <?= $generator->modelClass?>|array|\yii\db\ActiveRecord
    * @throws <?= $generator->notFoundExceptionClass?>

    */
    public function get<?= $generator->modelClass?>($id)
    {
        return $this-><?= $generator->modelLowerCamelCase?>Repository->get<?= $generator->modelClass?>ById($id);
    }

    /**
    * @param <?= $generator->modelClass?> $model
    * @throws \Throwable
    * @throws StaleObjectException
    */
    public function delete<?= $generator->modelClass?>(<?= $generator->modelClass?> $model): void
    {
        $model->delete();
    }
}