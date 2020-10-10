<?php

namespace app\modules\apiLogger\components;

use app\modules\apiLogger\factory\FileLoggerFactory;
use SplFileObject;
use yii\data\BaseDataProvider;

class LogDataProvider extends BaseDataProvider
{
    /**
     * @var string имя файла для чтения
     */
    public $filename;

    /**
     * @var string|callable имя столбца с ключом или callback-функция, возвращающие его
     */
    public $key;

    /**
     * @var SplFileObject
     */
    protected $fileObject;

    /** @var FileLoggerFactory */
    protected $fileLoggerFactory;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (!file_exists($this->filename)) {
            return ;
        }
        $this->fileObject = new SplFileObject ($this->filename);
        $this->fileObject->setFlags(SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);

        $this->fileLoggerFactory = \Yii::createObject(FileLoggerFactory::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareModels()
    {
        $models = [];
        if (!file_exists($this->filename)) {
            return [];
        }

        $pagination = $this->getPagination();

        if ($pagination === false) {
            throw new \Exception('Не работает без пагинации'); // @todo доделать
//            while (!$this->fileObject->eof()) {
//                $models[] = $this->fileObject->fgets();
////                $this->fileObject->next();
//            }
        } else {
            $pagination->totalCount = $this->getTotalCount();
            $this->fileObject->seek($pagination->getOffset());
            $limit = $pagination->getLimit();

            for ($count = 0; $count < $limit; ++$count) {
                if ($this->fileObject->current()) {
                    $models[] = $this->fileLoggerFactory->createFromLogLine($this->fileObject->current());
                    $this->fileObject->seek($pagination->getOffset() + $count + 1);
                }
            }
        }

        return $models;
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareKeys($models)
    {
        if ($this->key !== null) {
            $keys = [];

            foreach ($models as $model) {
                if (is_string($this->key)) {
                    $keys[] = $model[$this->key];
                } else {
                    $keys[] = call_user_func($this->key, $model);
                }
            }

            return $keys;
        }

        return array_keys($models);
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareTotalCount()
    {
        $count = 0;
        while (!$this->fileObject->eof()) {
            $this->fileObject->next();
            ++$count;
        }
        return $count - 1;
    }
}