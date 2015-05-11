<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Factory\Magic;

use FOF30\Container\Container;
use FOF30\Controller\DataController;
use FOF30\Factory\Exception\ControllerNotFound;

defined('_JEXEC') or die;

abstract class BaseFactory
{
	/**
	 * @var   Container|null  The container where this factory belongs to
	 */
	protected $container = null;

	/**
	 * Public constructor
	 *
	 * @param   Container  $container  The container we belong to
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}
}