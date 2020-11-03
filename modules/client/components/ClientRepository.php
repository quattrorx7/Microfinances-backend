<?php

namespace app\modules\client\components;

use app\components\BaseRepository;
use app\components\exceptions\UnSuccessModelException;
use app\models\Client;
use app\modules\client\exceptions\ClientNotFoundException;
use Yii;
use yii\db\Exception;

class ClientRepository extends BaseRepository
{

    /**
    * @param int $id
    * @return Client|array|\yii\db\ActiveRecord
    * @throws ClientNotFoundException    */
    public function getClientById(int $id)
    {
        $model = Client::find()
            ->where(['id' => $id])
            ->one();

        if (!$model) {
            throw new ClientNotFoundException('Client не найден');
        }

        return $model;
    }

    public function getClientsCountByPhone(string $phone)
    {
        return Client::find()
            ->where(['phone' => $phone])
            ->orWhere(['additional_phone' => $phone])
            ->all();
    }

    /**
     * @param Client $model
     * @throws UnSuccessModelException
     * @throws Exception
     */
    public function saveClient(Client $model): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->safeSave();
            $relatedRecords = $model->getRelatedRecords();

            if (isset($relatedRecords['district'])) {
                $model->link('district', $relatedRecords['district']);
            }
            if (isset($relatedRecords['owner'])) {
                $model->link('owner', $relatedRecords['owner']);
            }

            if (isset($relatedRecords['files'])) {
                foreach ($relatedRecords['files'] as $file) {
                    $model->link('files', $file);
                }
            }

            $transaction->commit();
            $model->refresh();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    /**
     * @param $search
     * @return array
     */
    public function getBySearch($search): array
    {
        $query = Client::find();

        if ($search) {
            $query->andWhere(['LIKE', "CONCAT(surname,' ',name,' ',patronymic)", $search]);
        }

        return $query->all();
    }
}