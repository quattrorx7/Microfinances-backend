<?php

/** @var app\components\gii\generators\api\Generator $generator */

echo "<?php\n";
?>

namespace <?= $generator->moduleNamespace?>;

use app\components\populator\AbstractPopulator;
use app\models\<?= $generator->modelClass?>;
use <?= $generator->formsNamespace?>\<?= $generator->createFormClass?>;
use <?= $generator->formsNamespace?>\<?= $generator->updateFormClass?>;

class <?= $generator->populatorClass?> extends AbstractPopulator
{

    public function populateFromCreateForm(<?= $generator->modelClass?> $model, <?= $generator->createFormClass?> $form): self
    {
        $this->populateAttributes($model, $form->attributes, [

        ]);

        return $this;
    }

    public function populateFromUpdateForm(<?= $generator->modelClass?> $model, <?= $generator->updateFormClass?> $form): self
    {
        $this->populateAttributes($model, $form->attributes, [

        ]);

        return $this;
    }

}