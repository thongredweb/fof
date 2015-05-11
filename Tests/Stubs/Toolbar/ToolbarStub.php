<?php
/**
 * @package        FOF
 * @subpackage     tests.stubs
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace FOF30\Tests\Stubs\Toolbar;

use FOF30\Toolbar\Toolbar;

class ToolbarStub extends Toolbar
{
    /** @var array Simply counter to check if a specific function is called */
    public    $methodCounter = array();

    public function onFoobarsDummy()
    {
        if(isset($this->methodCounter['onFoobarsDummy']))
        {
            $this->methodCounter['onFoobarsDummy']++;
        }
        else
        {
            $this->methodCounter['onFoobarsDummy'] = 1;
        }
    }

    public function onViews()
    {
        if(isset($this->methodCounter['onViews']))
        {
            $this->methodCounter['onViews']++;
        }
        else
        {
            $this->methodCounter['onViews'] = 1;
        }
    }

    public function onTask()
    {
        if(isset($this->methodCounter['onTask']))
        {
            $this->methodCounter['onTask']++;
        }
        else
        {
            $this->methodCounter['onTask'] = 1;
        }
    }
}