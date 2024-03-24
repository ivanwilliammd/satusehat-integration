<?php

use PHPUnit\Framework\TestCase;

class ArchTest extends TestCase
{
    public function testDebuggingFunctionsNotUsed()
    {
        $this->expect(['dd', 'dump', 'ray'])->not->toBeUsed();
    }
}
