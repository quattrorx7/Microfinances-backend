<?php

namespace app\modules\district\components;

use app\components\BaseService;
use app\components\exceptions\UnSuccessModelException;
use app\models\District;
use app\modules\district\exceptions\DistrictNotFoundException;
use app\modules\district\forms\DistrictCreateForm;
use app\modules\district\forms\DistrictUpdateForm;
use yii\db\StaleObjectException;

class DistrictService extends BaseService
{

    protected DistrictFactory $districtFactory;

    protected DistrictRepository $districtRepository;

    protected DistrictPopulator $districtPopulator;

    public function injectDependencies(DistrictFactory $districtFactory, DistrictRepository $districtRepository, DistrictPopulator $districtPopulator): void
    {
        $this->districtFactory = $districtFactory;
        $this->districtRepository = $districtRepository;
        $this->districtPopulator = $districtPopulator;
    }

    /**
    * @param DistrictCreateForm $form
    * @return District
    * @throws UnSuccessModelException
    */
    public function createByForm(DistrictCreateForm $form): District
    {
        $model = $this->districtFactory->create();
        $this->districtPopulator
            ->populateFromCreateForm($model, $form);

        $this->districtRepository->save($model);

        return $model;
    }

    /**
    * @param District $model
    * @param DistrictUpdateForm $form
    * @return District
    * @throws UnSuccessModelException
    */
    public function updateByForm(District $model, DistrictUpdateForm $form): District
    {
        $this->districtPopulator
            ->populateFromUpdateForm($model, $form);

        $this->districtRepository->save($model);

        return $model;
    }

    /**
    * @param $id
    * @return District|array|\yii\db\ActiveRecord
    * @throws DistrictNotFoundException
    */
    public function getDistrict($id)
    {
        return $this->districtRepository->getDistrictById($id);
    }

    /**
    * @param District $model
    * @throws \Throwable
    * @throws StaleObjectException
    */
    public function deleteDistrict(District $model): void
    {
        $model->delete();
    }
}