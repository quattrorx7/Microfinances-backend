<?php

namespace app\components\controllers;

use app\components\constants\UrlConst;
use app\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\IdentityInterface;

/**
 *
 * @property User $currentUser
 */
class AuthedAdminController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            $this->redirect(Url::to(UrlConst::LOGIN_ADMIN_PAGE));
            return false;
        }

        if (!Yii::$app->user->isSuperadmin) {
            $this->redirect(Url::to(UrlConst::LOGIN_ADMIN_PAGE));
            return false;
        }

        return parent::beforeAction($action);
    }

    /**
     * @return User|IdentityInterface
     */
    public function getCurrentUser(): User
    {
        return Yii::$app->user->identity;
    }
}