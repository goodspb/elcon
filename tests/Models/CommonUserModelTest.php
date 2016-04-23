<?php

namespace Tests\Models;

use Tests\TestCase;

class CommonUserModelTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testHelloWorld()
    {
        $this->assertContains('hello', 'hello world');
    }
}
