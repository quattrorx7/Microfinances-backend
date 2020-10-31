<?php

namespace app\modules\advance\forms;

use app\components\exceptions\ValidateException;
use yii\base\Model;
use yii\web\UploadedFile;

class AdvanceNoteForm extends Model
{
    public $note;

    public function rules(): array
    {
        return [
            [['note'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
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
        $self->note = UploadedFile::getInstanceByName('note');
        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateException($self);
    }
}