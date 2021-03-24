<?php
namespace Mezon\Cache\Tests;

use Mezon\Cache\Cache;

class CacheMock extends Cache
{

    /**
     * Cache content
     *
     * @var string
     */
    public $content = '';

    /**
     * Fetching cache data
     *
     * @param string $filePath
     *            path to cache file
     * @return string cache file content
     * @codeCoverageIgnore
     */
    protected function fileGetContents(string $filePath): string
    {
        return $this->content;
    }

    /**
     * Method stores data on disk
     *
     * @param string $path
     * @param string $content
     */
    protected function filePutContents(string $path, string $content): void
    {
        $this->content = $content;
    }
}
