<?php

use PHPUnit\Framework\TestCase;

class ArchTest extends TestCase
{
    public function test_debugging_functions_not_used()
    {
        $debuggingFunctions = ['dd', 'dump', 'ray'];
        $this->assertNotContainsAny($debuggingFunctions);
    }

    private function assertNotContainsAny(array $debuggingFunctions)
    {
        foreach ($debuggingFunctions as $function) {
            $this->assertNotContains($function, get_defined_functions()['user']);
        }
    }
}
