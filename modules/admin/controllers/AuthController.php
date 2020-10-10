<?php

namespace app\modules\admin\controllers;

use app\components\constants\UrlConst;
use app\modules\admin\models\forms\AdminLoginForm;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\Response;

class AuthController extends Controller
{
    /**
     * @return string|Response
     * @throws InvalidConfigException
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(UrlConst::DEFAULT_ADMIN_PAGE);
        }

        $this->layout = '@app/modules/admin/views/layouts/main-login';

        $model = Yii::createObject(AdminLoginForm::class);

        if ($model->load(Yii::$app->request->bodyParams) && $model->loginUser()) {
            return $this->redirect(UrlConst::DEFAULT_ADMIN_PAGE);
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->redirect(UrlConst::LOGIN_ADMIN_PAGE);
    }
}
