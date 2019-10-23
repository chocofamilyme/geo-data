<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use ReflectionProperty;

class Unit extends \Codeception\Module
{

    /**
     * Открывает приватные методы
     *
     * @param       $object
     * @param       $methodName
     * @param array $parameters
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Открывает приватные свойства
     *
     * @param       $object
     * @param       $propertyName
     * @param       $value
     *
     * @throws \ReflectionException
     */
    public function invokeProperty(&$object, $propertyName, $value = null)
    {
        $r = new ReflectionProperty(get_class($object), $propertyName);
        $r->setAccessible(true);
        $r->setValue($object, $value);
    }

}
