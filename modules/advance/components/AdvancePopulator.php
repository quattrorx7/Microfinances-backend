<?php

namespace app\modules\advance\components;

use app\components\populator\AbstractPopulator;
use app\models\Advance;
use app\models\Client;
use app\models\User;
use app\modules\advance\forms\AdvanceCreateForm;
use app\modules\advance\forms\AdvanceCreateWithClientForm;
use app\modules\advance\forms\AdvanceUpdateForm;

class AdvancePopulator extends AbstractPopulator
{

    public function populateFromCreateWithClientForm(Advance $model, AdvanceCreateWithClientForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'amount',
            'created_at',
            'limitation'
        ]);

        return $this;
    }

    public function populateClient(Advance $model, Client $client): self
    {
        $model->populateRelation('client', $client);
        return $this;
    }


    public function populateUser(Advance $model, User $user): self
    {
        $model->populateRelation('user', $user);
        return $this;
    }


    public function populateFromCreateForm(Advance $model, AdvanceCreateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'amount',
            'created_at',
            'limitation'
        ]);

        return $this;
    }

    public function populateFromUpdateForm(Advance $model, AdvanceUpdateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [

        ]);

        return $this;
    }

}