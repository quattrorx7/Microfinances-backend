<?php

namespace app\modules\admin\controllers;

use app\components\exceptions\UnSuccessModelException;
use app\modules\district\components\DistrictService;
use app\modules\district\forms\DistrictCreateForm;
use app\modules\district\forms\DistrictUpdateForm;
use app\modules\district\providers\DistrictProvider;
use Yii;
use app\components\controllers\AuthedAdminController;
use yii\filters\VerbFilter;
use yii\web\Response;

class DistrictController extends AuthedAdminController
{
    protected DistrictService $districtService;

    protected DistrictProvider $districtProvider;

    public function injectDependencies(DistrictService $districtService, DistrictProvider $districtProvider): void
    {
        $this->districtService = $districtService;
        $this->districtProvider = $districtProvider;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        [$searchModel, $dataProvider] = $this->districtProvider->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|Response
     * @throws UnSuccessModelException
     */
    public function actionCreate()
    {
        $form = new DistrictCreateForm();
        $form->load(Yii::$app->request->post());

        if (Yii::$app->request->isPost && $form->validate()) {
            $this->districtService->createByForm($form);
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->districtService->getDistrict($id);
        $form = DistrictUpdateForm::loadAndValidate($model->attributes);

        $form->load(Yii::$app->request->post());

        if (Yii::$app->request->isPost && $form->validate()) {
            $this->districtService->updateByForm($model, $form);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'form' => $form,
            'model' => $model,
        ]);
    }
}
