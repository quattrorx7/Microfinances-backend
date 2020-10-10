<?php

namespace app\models\forms;

use app\models\User;
use app\modules\user\components\UserRepository;
use yii\base\InvalidConfigException;
use yii\base\Model;
use Yii;

/**
 * Class LoginForm
 * @package app\models\forms
 *
 * @property User|null $user
 */
class LoginForm extends Model
{

    public ?string $email = null;

    public ?string $password = null;

    public bool $rememberMe = true;

    /** @var bool|User|null */
    protected $_user = false;

    protected UserRepository $userRepository;

    /**
     * LoginForm constructor.
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct( $config = [])
    {
        $this->userRepository = Yii::createObject(UserRepository::class);
        parent::__construct($config);
    }

    public function attributeLabels(): array
    {
        return [
            'email'    => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
        ];
    }

    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            [['email', 'password'], 'string'],
            ['email', 'email'],
            ['email', 'trim'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword(): void
    {
        $user = $this->getUser();

        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError('password', Yii::t('app', 'Invalid Email or Password'));
        }
    }

    /**
     * @return bool
     */
    public function loginUser(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? Yii::$app->user->cookieLifetime : 0);
        }

        return false;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->_user === false) {
            $this->_user = $this->userRepository->getActiveUserByEmail($this->email);
        }

        return $this->_user;
    }

}