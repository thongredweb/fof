<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers;

use FOF30\Container\Container;
use FOF30\Tests\Helpers\TestJoomlaPlatform;

/**
 * A specialised container for use in Unit Testing
 */
class TestContainer extends Container
{
	public function __construct(array $values = array())
	{
        if(!isset($values['componentName']))
        {
            $values['componentName'] = 'com_fakeapp';
        }

        if(!isset($values['platform']))
        {
            $values['platform'] = function(Container $c)
            {
                return new TestJoomlaPlatform($c);
            };
        }

		return parent::__construct($values);
	}
}