<?php

namespace app\components\serializers;

use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class Serializer
 * @package app\components\serializers
 */
class Serializer
{

    private array $properties = [];

    /**
     * Serializer constructor.
     * @param mixed ...$serializers
     *
     * @throws Exception
     */
    public function __construct(...$serializers)
    {
        foreach ($serializers as $serializer) {
            $this->addProperties($serializer);
        }
    }

    /**
     * @param mixed ...$serializers
     *
     * @return Serializer
     * @throws Exception
     */
    public static function create(...$serializers): Serializer
    {
        return new static($serializers);
    }

    /**
     * @param string|AbstractProperties $serializer
     * @return $this
     * @throws Exception
     */
    public function addProperties($serializer): self
    {
        /** @var AbstractProperties $serializer */
        $serializer = $serializer instanceof AbstractProperties ? $serializer : new $serializer();

        foreach ($serializer->getProperties() as $className => $classDescription) {

            if (is_string($classDescription)) {
                $this->addProperties($classDescription);
                continue;
            }

            if (array_key_exists($className, $this->properties)) {
                if (array_diff(array_keys($this->properties[$className]), array_keys($classDescription))) {
                    throw new Exception("Serializer already have params for {$className}");
                }
                continue;
            }
            $this->properties[$className] = $classDescription;
        }

        return $this;
    }

    /**
     * Serialize object to array
     *
     * @param $object
     * @return array
     */
    public function serialize($object): array
    {
        return ArrayHelper::toArray($object, $this->properties);
    }
}