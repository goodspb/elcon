<?php

namespace Tests\Controllers;

use Tests\ControllerTestCase;

class HomeIndexControllerTest extends ControllerTestCase
{

    public function testHelloWorld()
    {
        $this->assertContains('hello', 'hello world');
    }
}
