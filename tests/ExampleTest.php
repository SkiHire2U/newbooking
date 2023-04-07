<?php

class ExampleTest extends BrowserKitTestCase
{
    /**
     * A basic functional test example.
     */
    public function testBasicExample(): void
    {
        $this->visit('/')
             ->see('Laravel');
    }
}
