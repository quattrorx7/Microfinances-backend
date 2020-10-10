<?php

namespace app\components\serializers;

use yii\base\BaseObject;
use yii\base\Exception;

/**
 * Class AbstractProperties
 * @package app\components\serializers
 *
 * @property array $properties
 */
abstract class AbstractProperties extends BaseObject
{
    /**
     * @return array
     */
    abstract public function getProperties(): array;

    public static $params;

    /**
     * Create serializer for current class
     *
     * @param $params
     * @return Serializer
     *
     * @throws Exception
     */
    public static function createSerializer($params): Serializer
    {
        self::$params = $params;
        return new Serializer(static::class);
    }

    /**
     * Serialize use current class
     *
     * @param $object
     * @param $params
     * @return array
     * @throws Exception
     */
    public static function serialize($object, $params = null): array
    {
        return static::createSerializer($params)->serialize($object);
    }
}