<?php

namespace app\modules\api\serializer\advance;

use app\models\Advance;
use app\components\serializers\AbstractProperties;
use app\modules\advance\components\AdvanceRepository;
use app\modules\advance\formatters\AdvanceIssueDateFormatter;

class AdvanceShortSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            Advance::class => [
                'id',
                'issue_date' => function(Advance $advance) {
                    return AdvanceIssueDateFormatter::formatter($advance);
                },
                'amount',
                'refinancing' => function(Advance $model) {
                    if($model->isRefinancing()){
                        $ids = $model->refinancingIds();
                        $repository = new AdvanceRepository();
                        $advances = $repository->getRefinancingById($ids);
                        return ActiveAdvanceSerializer::serialize($advances);
                    }else{
                        return null;
                    }
                }
            ]
        ];
    }

}