<?php

use Easy\Redis\Redis;

class RedisClientTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $client = new Redis('127.0.0.1', 6379);
        $this->assertTrue(is_object($client));
        $this->assertTrue($client instanceof Redis);

        return $client;
    }

    /**
     * @depends testInstance
     */
    public function testStrings($client)
    {
        $client->del('test:key');
        $this->assertEquals('OK', $client->set('test:key', 'value'));
        $this->assertEquals('value', $client->get('test:key'));
        $this->assertEquals('1', $client->del('test:key'));
        $this->assertEmpty($client->get('test:key'));

        return $client;
    }

    /**
     * @depends testStrings
     */
    public function testSets($client)
    {
        $client->del('test:key');
        $this->assertEquals('1', $client->sadd('test:key', 'item1'));
        $this->assertEquals('1', $client->sadd('test:key', 'item2'));
        $this->assertEquals('2', $client->scard('test:key'));
        $this->assertEquals('1', $client->del('test:key'));
        $this->assertEquals('0', $client->scard('test:key'));
        $this->assertEmpty($client->smembers('test:key'));

        return $client;
    }

    /**
     * @depends testSets
     */
    public function testMulti($client)
    {
        $client->del('test:key');
        $this->assertEquals('1', $client->sadd('test:key', 'item1'));
        $this->assertEquals('1', $client->sadd('test:key', 'item2'));
        $this->assertEquals('OK', $client->multi());
        $this->assertEquals('QUEUED', $client->smembers('test:key'));
        $this->assertEquals('QUEUED', $client->smembers('test:key'));

        return $client;
    }

    /**
     * @depends testMulti
     */
    public function testException($client)
    {
        $this->setExpectedException('Exception');
        $this->assertEquals('OK', $client->set('test:key', 'value'));
        $client->sadd('test:key', 'item1', 'item2');
    }
}
