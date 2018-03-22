<?php

namespace µ;

use DirectoryIterator;
use Gestalt\Configuration;
use Jasny;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

/**
 * Provides access to the Gestalt configuration package.
 *
 * @return Configuration
 * @see https://github.com/samrap/gestalt
 */
function config()
{
    static $config;

    if ($config instanceof Configuration === true) {
        return $config;
    }

    return $config = new class extends Configuration {

        /**
         * Appends a single configuration file or all files
         * from a given directory into the current configuration.
         *
         * @param string|SplFileInfo $path Path of configuration file or directory.
         * @param string $preferredExtension Preferred files to use when scanning directory. Defaults to `php`.
         * @return Configuration
         */
        public function append($path, string $preferredExtension = 'php'): Configuration
        {
            $loaders = [
                'ini' => function ($file) {
                    return parse_ini_file($file, true);
                },
                'json' => function ($file) {
                    return json_decode(file_get_contents($file));
                },
                'php' => function ($file) {
                    return require $file;
                },
                'yaml' => function ($file) {
                    return Yaml::parseFile($file, Yaml::PARSE_CONSTANT);
                }
            ];

            $resource = $path instanceof SplFileInfo ? $path : new SplFileInfo($path);
            $extension = $resource->getExtension();

            if ($resource->isReadable() === false) {
                throw new RuntimeException("Unable to read “{$resource->getPathname()}”.");
            }

            if ($resource->isFile() && isset($loaders[$extension])) {
                $loader = $loaders[$extension];
                $append = new static;

                foreach ($loader($resource->getPathname()) as $key => $value) {
                    $append->set($key, $value);
                }

                $append = $append->all();
                $this->original = array_merge($this->original, $append);
                $this->items = array_merge($this->items, $append);

                $this->notify();

                return $this;
            }

            if ($resource->isDir()) {
                $directory = new DirectoryIterator(realpath($path));

                foreach ($directory as $file) {
                    if ($file->isFile() && $file->getExtension() === $preferredExtension) {
                        $this->append($file);
                    }
                }
            }

            return $this;
        }

        /**
         * Get a configuration item.
         *
         * @param string $key Key to look for.
         * @param mixed $default Default data to return if key wasn’t found.
         * @param bool $noCast Do not cast arrays to objects. Default: false
         * @return mixed
         */
        public function get($key, $default = null, $noCast = false)
        {
            $result = parent::get($key, $default);

            if ($noCast === true) {
                return $result;
            }

            return Jasny\objectify($result);
        }
    };
}
