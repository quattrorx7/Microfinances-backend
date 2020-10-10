<?php

namespace app\components\yii;

use ReflectionClass;
use ReflectionException;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\di\NotInstantiableException;

class Container extends \yii\di\Container {
    /**
     * @param string $class
     * @param array $params
     * @param array $config
     *
     * @return object
     * @throws ReflectionException
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    protected function build($class, $params, $config)
    {
        $object = parent::build($class, $params, $config);
        [$reflection, $dependencies] = $this->getDependencies($class);

        if ($reflection) {
            $reflection = new ReflectionClass($class);
            if ($reflection->hasMethod('injectDependencies')) {
                $methodDependencies = [];
                $diMethod = $reflection->getMethod('injectDependencies');
                foreach ($diMethod->getParameters() as $param) {
                    if ($param->isDefaultValueAvailable()) {
                        $methodDependencies[] = $param->getDefaultValue();
                    } else {
                        $c = $param->getClass();
                        $methodDependencies[] = Instance::of($c === null ? null : $c->getName());
                    }
                }
                if ($methodDependencies) {
                    $methodDependencies = $this->resolveDependencies($methodDependencies, $reflection);
                    call_user_func_array([$object,'injectDependencies'], $methodDependencies);
                }
            }
        }
        return $object;
    }
}
