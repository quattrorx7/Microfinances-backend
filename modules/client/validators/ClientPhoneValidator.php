<?php

namespace app\modules\client\validators;

use app\models\Client;
use app\modules\client\components\ClientRepository;

class ClientPhoneValidator
{
    protected ClientRepository $clientRepository;

    public function __construct()
    {
        $this->clientRepository = \Yii::createObject(ClientRepository::class);
    }

    public function validate(Client $client, $attribute = 'phone'): bool
    {
        if (!$client->$attribute) {
            return true;
        }

        $models = $this->clientRepository
            ->getClientsCountByPhone($client->$attribute);

        if ($client->isNewRecord) {
            if (count($models) !== 0) {
                $client->addError($attribute, 'Такой номер телефона уже зарегистрирован');
                return false;
            }
            return true;
        }

        if (count($models) > 1) {
            $client->addError('phone', 'Такой номер телефона уже зарегистрирован');
            return false;
        }

        return true;
    }

}