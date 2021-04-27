<?php

namespace app\modules\client\forms;

use app\components\exceptions\ValidateException;
use yii\base\Model;
use yii\web\UploadedFile;

class ClientFileNoteForm extends Model
{
    public $note;

    public function rules(): array
    {
        return [
            [['note'], 'each', 'rule' => ['file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles'=>1]]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'note' => 'Расписка'
        ];
    }

    /**
    * @param $bodyParams
    * @param string $formName
    * @return static
    * @throws ValidateException
    */
    public static function loadAndValidate($bodyParams, $formName = ''): self
    {
        $self = new self();
        $self->note = UploadedFile::getInstancesByName('note');

        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateException($self);
    }
}