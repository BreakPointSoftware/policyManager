<?php

namespace App\Test\Utility\TestHelpers;
Use ReflectionClass;

trait HiddenMethodInvokerTrait {

    /**
     * Call protected/private method of a class.
     *
     * @param object &$objectByRef    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$objectByRef, $methodName, array $parameters = array())
    {
        $reflection = new ReflectionClass(get_class($objectByRef));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($objectByRef, $parameters);
    }

}