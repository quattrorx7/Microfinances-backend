<?php

namespace app\modules\admin\controllers;

use app\components\controllers\AuthedAdminController;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends AuthedAdminController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
