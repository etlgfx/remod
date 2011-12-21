<?php

class ConfigTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     * @expectedException InvalidConfigException
     * @expectedExceptionMessage Invalid config key: key.does.not.exist
     */
    public function invalidKeyThrowsException() {
        Config::read('key.does.not.exist');
    }
}