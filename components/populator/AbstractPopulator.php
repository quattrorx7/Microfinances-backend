<?php

namespace app\components\populator;

use yii\base\BaseObject;
use yii\helpers\Inflector;

class AbstractPopulator extends BaseObject
{
    protected function populateAttributes($model, $data, $attributes): void
    {
        foreach ($attributes as $attribute) {
            if (array_key_exists($attribute, $data)) {
                $model->$attribute = $data[$attribute];
            }
            $attributeCamelCase = Inflector::variablize($attribute);
            if (($attribute !== $attributeCamelCase) && array_key_exists($attributeCamelCase, $data)) {
                $model->$attribute = $data[$attributeCamelCase];
            }
        }
    }
}
