<?php

namespace app\modules\advance\components;

use app\helpers\DateHelper;
use app\models\User;
use app\modules\client\forms\ClientSearchForm;
use yii\base\BaseObject;
use yii\db\ActiveQuery;

class AdvanceManager extends BaseObject
{

    protected AdvanceRepository $advanceRepository;

    public function injectDependencies(AdvanceRepository $advanceRepository): void
    {
        $this->advanceRepository = $advanceRepository;
    }

    public function search(ClientSearchForm $form, User $user)
    {
        $query = $this->advanceRepository->createSearchQuery();

        if (!$user->isSuperadmin) {
            $query->andWhere(['user_id' => $user->id]);
        }

        if ($search = $form->search) {
            $query
                ->innerJoinWith(['client' => static function(ActiveQuery $activeQuery) use ($search) {
                    $activeQuery->andOnCondition(['LIKE', "CONCAT(surname,' ',name,' ',patronymic)", $search]);
                }]);
        }

        return $query->all();
    }

    public function debts(User $user)
    {
        $query = $this->advanceRepository->createDebtQuery();

        if (!$user->isSuperadmin) {
            $query->andWhere(['advance.user_id' => $user->id]);
        }

        $query
            ->joinWith(['paymentHistories'=>function($query){
                $query->onCondition(['DATE(payment_history.created_at)'=>DateHelper::nowWithoutHours()]);
                $query->andOnCondition(['!=', 'payment_history.amount', 0]);
            }])
            ->addSelect(['IFNULL(payment_history.id, 0) as todayPayed'])
            ->addSelect(['SUM(summa_left_to_pay) as debt'])
            ->groupBy('advance.client_id')
            ->orderBy('advance.id DESC');

        return $query->all();
    }

}