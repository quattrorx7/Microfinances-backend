<?php

namespace app\modules\advance\components;

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

        return $query->all();
    }

}