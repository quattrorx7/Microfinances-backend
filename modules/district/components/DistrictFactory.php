<?php

namespace app\modules\district\components;

use app\models\District;
use app\components\BaseFactory;

class DistrictFactory extends BaseFactory

{
    /**
     * @return District
     */
    public function create(): District
    {
        return new District();
    }
}