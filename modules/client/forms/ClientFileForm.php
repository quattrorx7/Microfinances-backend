<?php

namespace app\modules\client\forms;

use app\components\exceptions\ValidateException;
use yii\base\Model;
use yii\web\UploadedFile;

class ClientFileForm extends Model
{
    public $files;

    public function rules(): array
    {
        return [
            [['files'], 'each', 'rule' => ['file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg']]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'files' => 'Фотографии'
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
        $self->files = UploadedFile::getInstancesByName('files');

        $self->load($bodyParams, $formName);

        if ($self->validate()) {
            return $self;
        }

        throw new ValidateException($self);
    }
}