<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Stubs\Event;

use FOF30\Event\Observer;

class FirstObserver extends Observer
{
	public $myId = 'one';

	public function returnConditional($stuff)
	{
		return ($stuff == $this->myId);
	}

	public function identifyYourself()
	{
		return $this->myId;
	}

	public function chain($stuff)
	{
		if ($stuff == $this->myId)
		{
			return $this->myId;
		}

		return null;
	}
}