<?php

/** @var app\components\gii\generators\api\Generator $generator */

echo "<?php\n";

?>

namespace <?= $generator->formsNamespace?>;

use <?= $generator->exceptionsNamespace?>\Validate<?= $generator->modelClass?>CreateException;
use yii\base\Model;

class <?= $generator->modelClass?>CreateForm extends Model
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
    * @throws Validate<?= $generator->modelClass?>CreateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new Validate<?= $generator->modelClass?>CreateException($self);
    }
}