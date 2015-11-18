<?php

require 'AccountFacade.php';

class AccountFacadeTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {

    }

    public function testPing()
    {
        $object = new AccountFacade();
        $this->assertTrue($object->ping());
    }
}