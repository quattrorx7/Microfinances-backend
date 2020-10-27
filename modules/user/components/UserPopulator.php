<?php

namespace app\modules\user\components;

use app\components\populator\AbstractPopulator;
use app\models\User;
use app\modules\user\forms\UserCreateForm;
use app\modules\user\forms\UserUpdateForm;

class UserPopulator extends AbstractPopulator
{

    public function populateFromCreateForm(User $model, UserCreateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'username',
            'fullname'
        ]);

        return $this;
    }

    public function populateFromUpdateForm(User $model, UserUpdateForm $form): self
    {
        $this->populateAttributes($model, $form->attributes, [
            'username',
            'fullname'
        ]);

        return $this;
    }

}