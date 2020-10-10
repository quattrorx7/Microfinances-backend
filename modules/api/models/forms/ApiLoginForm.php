<?php

namespace app\modules\api\models\forms;

use app\components\exceptions\authorization\FailedValidateAuthorizationException;
use app\models\forms\LoginForm;
use Yii;

class ApiLoginForm extends LoginForm
{
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

    public function loginUser(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 0);
        }

        return false;
    }

    public function validateIsAdmin(): void
    {
        $user = $this->getUser();

        if (!$user || $this->userRepository->getAdminByEmail($this->email)) {
            $this->addError('email', Yii::t('app', 'Administrators are not allowed to access API'));
        }
    }

    /**
     * @param $bodyParams
     * @param string $formName
     * @return ApiLoginForm
     * @throws FailedValidateAuthorizationException
     */
    public static function loadByRequestBodyParams($bodyParams, $formName = ''): ApiLoginForm
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new FailedValidateAuthorizationException($self);
    }
}