<?php
namespace Mezon\Cache;

/**
 * Class Cache
 *
 * @package Mezon
 * @subpackage Cache
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/17)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Class for caching data on disk.
 * For now we use one cache file for all pages of the service.
 * Cache drops each hour.
 *
 * @author Dodonov A.A.
 */
class Cache extends \Mezon\Singleton\Singleton
{

    /**
     * Cache data
     *
     * @var string
     */
    protected $data = null;

    /**
     * Cache file path
     *
     * @var string
     */
    protected $cachePath = './cache/';

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
        return (@file_get_contents($filePath));
    }

    /**
     * Method inits cache
     */
    protected function init()
    {
        if ($this->data === null) {
            $this->data = $this->fileGetContents($this->cachePath . date('YmdH') . '.cache');

            if ($this->data === false) {
                $this->data = [];
            } else {
                $this->data = $this->data == '' ? [] : json_decode($this->data, true);
            }
        }
    }

    /**
     * Method adds data to cache
     *
     * @param string $key
     *            Key
     * @param mixed $data
     *            Data to be stored in cache
     */
    public function set(string $key, $data)
    {
        $this->init();

        \Mezon\Functional\Functional::setField(
            $this->data,
            $key,
            [
                // giving us an ability to break reference of the object wich was passed in $data
                'data' => json_decode(json_encode($data))
            ]);
    }

    /**
     * Checking cache for data
     *
     * @param string $key
     *            Data key
     * @return bool True if the data was found, false otherwise
     */
    public function exists(string $key): bool
    {
        $this->init();

        return isset($this->data[$key]);
    }

    /**
     * Method gets data from cache
     *
     * @param string $key
     *            Key of the requested data
     * @return mixed Data from cache
     */
    public function get(string $key)
    {
        $this->init();

        if (\Mezon\Functional\Functional::fieldExists($this->data, $key, false) === false) {
            throw (new \Exception("The key $key does not exist"));
        }

        $keyValue = \Mezon\Functional\Functional::getField($this->data, $key, false);

        $result = \Mezon\Functional\Functional::getField($keyValue, 'data', false);

        // preventing external code from writing directly to cache
        return json_decode(json_encode($result));
    }

    /**
     * Method stores data on disk
     *
     * @param string $path
     * @param string $content
     */
    protected function filePutContents(string $path, string $content): void
    {
        @file_put_contents($path, $content);
    }

    /**
     * Method flushes data on disk
     */
    public function flush()
    {
        if ($this->data !== null) {
            $this->filePutContents($this->cachePath . date('YmdH') . '.cache', json_encode($this->data));

            $this->data = null;
        }
    }
}
