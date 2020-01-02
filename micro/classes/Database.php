<?php

declare(strict_types=1);

namespace µ;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class Database
{

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Database constructor.
     *
     * @param object $config Doctrine configuration.
     * @param string $env Current environment we’re running on.
     */
    public function __construct($config, $env)
    {
        $this->connection = DriverManager::getConnection((array) $config);

        $isDevMode = in_array(strtolower($env ?? ''), ['prod', 'production', 'live']) === false;
        $proxyDir = $config->proxy->path ?? null;
        $cache = null;
        $useSimpleAnnotationReader = $config->metadata->useSimple ?? true;

        if (isset($config->metadata)) {
            $args = [
                (array) ($config->metadata->path ?? null),
                $isDevMode,
                $proxyDir,
                $cache,
                $useSimpleAnnotationReader
            ];

            switch (strtolower($config->metadata->driver ?? '')) {
                case 'xml':
                    $this->config = Setup::createXMLMetadataConfiguration(...$args);
                    break;

                case 'yaml':
                case 'yml':
                    $this->config = Setup::createYAMLMetadataConfiguration(...$args);
                    break;

                default:
                    $this->config = Setup::createAnnotationMetadataConfiguration(...$args);
            }
        } else {
            $this->config = Setup::createConfiguration($isDevMode, $proxyDir);
        }

        $this->entityManager = EntityManager::create($this->connection, $this->config);

        // @see https://github.com/schmittjoh/serializer/issues/179
        AnnotationRegistry::registerLoader('class_exists');
    }

    /**
     * Returns the Doctrine Connection object.
     *
     * @return Connection
     */
    public function connection(): Connection
    {
        return $this->connection;
    }

    /**
     *
     * Returns the Doctrine EntityManager object.
     *
     * @return EntityManager
     */
    public function entity(): EntityManager
    {
        return $this->entityManager;
    }
}
