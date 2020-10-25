<?php

namespace app\modules\district\components;

use app\components\populator\AbstractPopulator;
use app\models\District;
use app\modules\district\forms\DistrictCreateForm;
use app\modules\district\forms\DistrictUpdateForm;

class DistrictPopulator extends AbstractPopulator
{

    public function populateFromCreateForm(District $model, DistrictCreateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'title'
        ]);

        return $this;
    }

    public function populateFromUpdateForm(District $model, DistrictUpdateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'title'
        ]);

        return $this;
    }

}