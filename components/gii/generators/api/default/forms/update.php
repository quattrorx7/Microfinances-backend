<?php

/** @var app\components\gii\generators\api\Generator $generator */

echo "<?php\n";

?>

namespace <?= $generator->formsNamespace?>;

use <?= $generator->exceptionsNamespace?>\Validate<?= $generator->modelClass?>UpdateException;
use yii\base\Model;

class <?= $generator->modelClass?>UpdateForm extends Model
{

    public function rules(): array
    {
        return [

        ];
    }

    public function attributeLabels(): array
    {
        return [

        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws Validate<?= $generator->modelClass?>UpdateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new Validate<?= $generator->modelClass?>UpdateException($self);
    }
}