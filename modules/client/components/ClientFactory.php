<?php

namespace app\modules\client\components;

use app\helpers\DateHelper;
use app\models\Client;
use app\components\BaseFactory;
use app\models\User;
use Exception;

class ClientFactory extends BaseFactory

{
    /**
     * @param User $owner
     * @return Client
     * @throws Exception
     */
    public function create(User $owner): Client
    {
        $model = new Client();
        $model->created_at = DateHelper::now();
        $model->populateRelation('owner', $owner);

        return $model;
    }
}