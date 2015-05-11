<?php
/**
 * @package        FOF
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace FOF30\Tests\Factory\Magic;

use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

/**
 * @covers      FOF30\Factory\Magic\BaseFactory::<protected>
 * @covers      FOF30\Factory\Magic\BaseFactory::<private>
 * @package     FOF30\Tests\Factory
 */
class BaseFactoryTest extends FOFTestCase
{
    /**
     * @covers      FOF30\Factory\Magic\BaseFactory::__construct
     */
    public function test__construct()
    {
        $container = static::$container;

        $factory = $this->getMockForAbstractClass('FOF30\Factory\Magic\BaseFactory', array($container));

        $this->assertSame($container, ReflectionHelper::getValue($factory, 'container'));
    }
}