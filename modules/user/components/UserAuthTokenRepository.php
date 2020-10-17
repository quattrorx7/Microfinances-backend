<?php

namespace app\modules\user\components;

use app\components\BaseRepository;
use app\components\exceptions\UnSuccessModelException;
use app\models\UserAuthToken;
use Yii;
use yii\db\Exception;

class UserAuthTokenRepository extends BaseRepository
{

    public function getByDeviceId(string $deviceId)
    {
        return UserAuthToken::find()
            ->andWhere(['device_id' => $deviceId])
            ->one();
    }

    /**
     * @param UserAuthToken $model
     * @throws UnSuccessModelException
     * @throws Exception
     */
    public function saveToken(UserAuthToken $model): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->safeSave();

            $relatedRecords = $model->getRelatedRecords();

            if (isset($relatedRecords['user'])) {
                $model->link('user', $relatedRecords['user']);
            }

            $transaction->commit();
            $model->refresh();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

    }
}