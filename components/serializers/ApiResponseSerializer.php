<?php

namespace app\components\serializers;

use app\components\JSendResponse;
use yii\base\Component;

class ApiResponseSerializer extends Component
{
    /**
     * @param $data
     *
     * @return array
     */
    public function serialize($data): array
    {
        if ($data instanceof JSendResponse) {
            return $data->toArray();
        }

        if ($data && !is_array($data)) {
            $data = [$data];
        }

        $jSendResponse = JSendResponse::success(null, $data);

        return $jSendResponse->toArray();
    }
}
