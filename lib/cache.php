<?php

namespace µ;

use CouchbaseCluster;
use InvalidArgumentException;
use League\Flysystem;
use MatthiasMullie\Scrapbook;
use Memcached;
use PDO;
use Redis;

/**
 * Provides access to the Scrapbook package.
 *
 * @return object|null
 * @see https://github.com/matthiasmullie/scrapbook
 */
function cache()
{
    static $cache;

    if (is_object($cache) === true) {
        return $cache;
    }

    $config = config()->get('µ.cache', null);

    if ($config === null) {
        return null;
    }

    $createCache = function ($config) {
        switch (strtolower($config->adapter)) {
            case 'memcached':
                $client = new Memcached();
                $client->addServer($config->host, $config->port);
                $scrapbook = new Scrapbook\Adapters\Memcached($client);

                break;

            case 'redis':
                $client = new Redis();
                $client->connect($config->host);
                $scrapbook = new Scrapbook\Adapters\Redis($client);

                break;

            case 'couchbase':
                $cluster = new CouchbaseCluster('couchbase://' . $config->host);
                $bucket = $cluster->openBucket($config->name);
                $scrapbook = new Scrapbook\Adapters\Couchbase($bucket);

                break;

            case 'apc':
            case 'apcu':
                $scrapbook = new Scrapbook\Adapters\Apc();

                break;

            case 'mysql':
                $client = new PDO(
                    "mysql:dbname={$config->name};host={$config->host}",
                    $config->user,
                    $config->password
                );
                $scrapbook = new Scrapbook\Adapters\MySQL($client);

                break;

            case 'sqlite':
                $client = new PDO("sqlite:{$config->path}");
                $scrapbook = new Scrapbook\Adapters\SQLite($client);

                break;

            case 'postgresql':
                $client = new PDO("pgsql:user={$config->user} dbname={$config->name} password={$config->password}");
                $scrapbook = new Scrapbook\Adapters\PostgreSQL($client);

                break;

            case 'memory':
                $scrapbook = new Scrapbook\Adapters\MemoryStore();

                break;

            case 'flysystem':
            case 'files':
                $adapter = new Flysystem\Adapter\Local($config->path ?? sys_get_temp_dir());
                $filesystem = new Flysystem\Filesystem($adapter);
                $scrapbook = new Scrapbook\Adapters\Flysystem($filesystem);

                break;

            default:
                throw new InvalidArgumentException("Given adapter “{$config->adapter}” is not supported.");
        }

        return $scrapbook;
    };

    if (isset($config->sharding) === true) {
        if (is_iterable($config->sharding) === false && is_object($config->sharding) === false) {
            throw new InvalidArgumentException('Sharding parameter needs to be iterable.');
        }

        $scrapbook = [];

        foreach ($config->sharding as $cacheConfig) {
            $scrapbook[] = $createCache($cacheConfig);
        }

        $scrapbook = new Scrapbook\Scale\Shard(...$scrapbook);
    } else {
        $scrapbook = $createCache($config);
    }

    if (empty($config->localBuffer) === false) {
        $scrapbook = new Scrapbook\Buffered\BufferedStore($scrapbook);
    }

    if (empty($config->stampedeProtection) === false) {
        $scrapbook = new Scrapbook\Scale\StampedeProtector($scrapbook);
    }

    if (empty($config->transactions) === false) {
        $scrapbook = new Scrapbook\Buffered\TransactionalStore($scrapbook);
    }

    if (empty($config->psr16) === false) {
        $cache = new Scrapbook\Psr16\SimpleCache($scrapbook);
    } elseif (empty($config->psr6) === false) {
        $cache = new Scrapbook\Psr6\Pool($scrapbook);
    } else {
        $cache = $scrapbook;
    }

    return $cache;
}
