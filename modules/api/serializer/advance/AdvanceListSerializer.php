<?php

namespace app\modules\api\serializer\advance;

use app\models\Advance;
use app\components\serializers\AbstractProperties;
use app\modules\advance\components\AdvanceRepository;
use app\modules\advance\formatters\AdvanceIssueDateFormatter;
use app\modules\advance\formatters\AdvanceStatusFormatter;
use app\modules\api\serializer\client\ClientShortSerializer;
use app\modules\api\serializer\files\FilesSerializer;

class AdvanceListSerializer extends AbstractProperties
{

    public function getProperties(): array
    {
        return [
            Advance::class => [
                'id',
                'status' => function(Advance $model) {
                    return AdvanceStatusFormatter::formatter($model);
                },
                'issue_date' => function(Advance $advance) {
                    return AdvanceIssueDateFormatter::formatter($advance);
                },
                'amount',
                'client',
                'note',
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
            ],
            ClientShortSerializer::class,
            FilesSerializer::class
        ];
    }

}