<?php

namespace FOF30\Tests\Stubs\Dispatcher;

use FOF30\Container\Container;
use FOF30\Dispatcher\Dispatcher;

class DispatcherStub extends Dispatcher
{
    private   $mockedMethods = array();

    /**
     * Assigns callback functions to the class, the $methods array should be an associative one, where
     * the keys are the method names, while the values are the closure functions, e.g.
     *
     * array(
     *    'foobar' => function(){ return 'Foobar'; }
     * )
     *
     * @param           $container
     * @param array     $config
     * @param array     $methods
     */
    public function __construct(Container $container, array $config = array(), array $methods = array())
    {
        foreach($methods as $method => $function)
        {
            $this->mockedMethods[$method] = $function;
        }

        parent::__construct($container, $config);
    }

    public function __call($method, $args)
    {
        if (isset($this->mockedMethods[$method]))
        {
            $func = $this->mockedMethods[$method];

            // Let's pass an instance of ourself, so we can manipulate other closures
            array_unshift($args, $this);

            return call_user_func_array($func, $args);
        }
    }

    public function onBeforeDispatch()
    {
        if(isset($this->mockedMethods['onBeforeDispatch']))
        {
            $func = $this->mockedMethods['onBeforeDispatch'];

            return call_user_func_array($func, array($this));
        }

        return true;
    }

    public function onAfterDispatch()
    {
        if(isset($this->mockedMethods['onAfterDispatch']))
        {
            $func = $this->mockedMethods['onAfterDispatch'];

            return call_user_func_array($func, array($this));
        }

        return true;
    }

    public function onBeforeDispatchCLI()
    {
        if(isset($this->mockedMethods['onBeforeDispatchCLI']))
        {
            $func = $this->mockedMethods['onBeforeDispatchCLI'];

            return call_user_func_array($func, array($this));
        }

        return true;
    }

    public function onAfterDispatchCLI()
    {
        if(isset($this->mockedMethods['onAfterDispatchCLI']))
        {
            $func = $this->mockedMethods['onAfterDispatchCLI'];

            return call_user_func_array($func, array($this));
        }

        return true;
    }
}
