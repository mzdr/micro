<?php

namespace µ;

use DirectoryIterator;
use Gestalt\Configuration;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

/**
 * Provides access to the Gestalt configuration package.
 *
 * @param array ...$args
 * @return \Gestalt\Configuration|mixed
 * @see https://github.com/samrap/gestalt
 */
function config(...$args) {
    static $config;

    if ($config instanceof Configuration === false) {
        $config = new class extends Configuration {

            /**
             * Appends a single configuration file or all files
             * from a given directory into the current configuration.
             *
             * @param $path Path of configuration file or directory.
             * @param string $preferredExtension Preferred files to use when scanning directory. Defaults to `php`.
             * @return self
             */
            public function append($path, string $preferredExtension = 'php'): self {
                $loaders = [
                    'ini' => function($file) { return parse_ini_file($file, true); },
                    'json' => function($file) { return json_decode(file_get_contents($file)); },
                    'php' => function($file) { return require $file; },
                    'yaml' => function($file) { return Yaml::parseFile($file); }
                ];

                $resource = $path instanceof SplFileInfo ? $path : new SplFileInfo($path);
                $extension = $resource->getExtension();

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
        };
    }

    $count = count($args);

    if ($count === 0) {
        return $config;
    }

    if ($count === 2) {
        $config->set(...$args);

        return $config;
    }

    if (is_array($args[0])) {
        foreach ($args[0] as $key => $value) {
            config($key, $value);
        }

        return $config;
    }

    return $config[$args[0]];
}
