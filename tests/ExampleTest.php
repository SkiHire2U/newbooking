<?php

class ExampleTest extends BrowserKitTestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample(): void
    {
        $this->visit('/')
             ->see('Laravel');
    }
}
