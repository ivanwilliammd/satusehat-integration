<?php

use PHPUnit\Framework\TestCase;

class ArchTest extends TestCase
{
    public function testDebuggingFunctionsNotUsed()
    {
        $this->assertNotContains('dd', ['dd', 'dump', 'ray']);
        $this->assertNotContains('dump', ['dd', 'dump', 'ray']);
        $this->assertNotContains('ray', ['dd', 'dump', 'ray']);
    }
}
