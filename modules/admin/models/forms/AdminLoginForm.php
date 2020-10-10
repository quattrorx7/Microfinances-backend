<?php

namespace app\modules\admin\models\forms;

use app\models\forms\LoginForm;

/**
 * Class AdminLoginForm
 * @package app\modules\admin\models\forms
 */
class AdminLoginForm extends LoginForm
{
    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        $parent = parent::rules();
        $parent[] = ['email', 'validateIsAdmin'];
        return $parent;
    }

    public function attributeLabels(): array
    {
        return parent::attributeLabels();
    }

    public function validateIsAdmin(): void
    {
        $user = $this->getUser();

        if (!$user || !$this->userRepository->getAdminByEmail($this->email)) {
            $this->addError('email', 'Недостаточно прав');
        }
    }
}
