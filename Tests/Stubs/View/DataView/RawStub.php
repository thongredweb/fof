<?php

namespace FOF30\Tests\Stubs\View\DataView;

use FOF30\Container\Container;
use FOF30\View\DataView\Raw;

class RawStub extends Raw
{
    private $methods = array();

    /**
     * Assigns callback functions to the class, the $methods array should be an associative one, where
     * the keys are the method names, while the values are the closure functions, e.g.
     *
     * array(
     *    'foobar' => function(){ return 'Foobar'; }
     * )
     *
     * @param       $container
     * @param array $config
     * @param array $methods
     */
    public function __construct(Container $container, array $config = array(), array $methods = array())
    {
        foreach ($methods as $method => $function) {
            $this->methods[$method] = $function;
        }

        parent::__construct($container, $config);
    }

    public function __call($method, $args)
    {
        if (isset($this->methods[$method])) {
            $func = $this->methods[$method];

            // Let's pass an instance of ourself, so we can manipulate other closures
            array_unshift($args, $this);

            return call_user_func_array($func, $args);
        }
    }
}
