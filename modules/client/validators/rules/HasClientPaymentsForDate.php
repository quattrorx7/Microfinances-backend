<?php

namespace app\modules\client\validators\rules;

use app\models\Client;
use app\modules\client\exceptions\ClientNotFoundPaymentsException;
use app\modules\payment\components\PaymentRepository;
use yii\helpers\ArrayHelper;

/**
 * Валидатор для проверки наличия необходимости платить
 *
 * Class HasClientPaymentsForDate
 * @package app\modules\client\validators\rules
 */
class HasClientPaymentsForDate
{
    protected PaymentRepository $paymentRepository;

    public function __construct()
    {
        $this->paymentRepository = \Yii::createObject(PaymentRepository::class);
    }

    public function validate(Client $client, string $date): void
    {
        $models = $this->paymentRepository->getPaysWithClientAndDate($client->id, $date);

        if (array_sum(ArrayHelper::getColumn($models, 'amount')) === 0 && $client->getAllDebts() === 0) {
            throw new ClientNotFoundPaymentsException(sprintf('На %s не найдено активных платежей', $date));
        }
    }
}