<?php
use PHPUnit\Framework\TestCase;

class TestExample extends TestCase {

    public function test_plugin_is_active() {
        $this->assertTrue( function_exists('my_custom_snippet') );
    }
}
