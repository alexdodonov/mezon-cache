<?php
namespace Mezon\Cache\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Cache\Cache;

class CacheUnitTest extends TestCase
{

    /**
     * Testing that data can be added to cache.
     */
    public function testAdditingDataToCache()
    {
        $cache = $this->getMockBuilder(Cache::class)
            ->setMethods([
            'flush'
        ])
            ->disableOriginalClone()
            ->getMock();

        $cache->set('key', 'test');

        $cache->flush();

        $result = $cache->get('key');

        $cache->destroy();

        $this->assertEquals('test', $result, 'Cache is not working');
    }

    /**
     * Method checks exists() method.
     */
    public function testExistence()
    {
        $cache = Cache::getInstance();

        $cache->set('key', 'test');

        $this->assertTrue($cache->exists('key'));
        $this->assertFalse($cache->exists('unexisting'));
    }

    /**
     * Method checks exists() method.
     */
    public function testExistenceObject(): void
    {
        // setup
        $cache = $this->getMockBuilder(Cache::class)
            ->setMethods([
            'fileGetContents'
        ])
            ->disableOriginalClone()
            ->getMock();

        $cache->method('fileGetContents')->willReturn('{"key":1}');

        // test body and assertions
        $this->assertTrue($cache->exists('key'));
    }

    /**
     * Testing get method
     */
    public function testGetUnexisting(): void
    {
        // setup
        $cache = Cache::getInstance();

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $cache->get('unexisting');
    }

    /**
     * Testing 'flush' method
     */
    public function testFlush(): void
    {
        // setup
        $cache = $this->getMockBuilder(Cache::class)
            ->setMethods([
            'filePutContents',
            'fileGetContents'
        ])
            ->disableOriginalClone()
            ->getMock();

        // assertions
        $cache->expects($this->once())
            ->method('filePutContents');
        $cache->method('fileGetContents')->willReturn('{"key":1}');

        // test body
        $cache->set('key', 'value');
        $cache->flush();
    }
}
