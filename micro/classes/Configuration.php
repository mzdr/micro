<?php

declare(strict_types=1);

namespace µ;

use DirectoryIterator;
use Gestalt;
use Jasny;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class Configuration extends Gestalt\Configuration
{
    /**
     * List of supported parsers.
     *
     * @var array
     */
    protected $parsers = [
        'ini' => 'parseIni',
        'json' => 'parseJson',
        'php' => 'parsePhp',
        'yaml' => 'parseYaml'
    ];

    /**
     * Parses an INI file and returns the content of it.
     *
     * @param string $filename Path to INI file.
     * @return array|bool
     */
    protected function parseIni($filename)
    {
        return parse_ini_file($filename, true);
    }

    /**
     * Parses a JSON file and returns the content of it.
     *
     * @param string $filename Path to JSON file.
     * @return mixed
     */
    protected function parseJson($filename)
    {
        return json_decode(file_get_contents($filename), true);
    }

    /**
     * Parses a PHP file and returns the content of it.
     *
     * @param string $filename Path to PHP file.
     * @return mixed
     */
    protected function parsePhp($filename)
    {
        return Jasny\arrayify(require $filename);
    }

    /**
     * Parses a YAML file and returns the content of it.
     *
     * @param string $filename Path to YAML file.
     * @return mixed
     */
    protected function parseYaml($filename)
    {
        return Yaml::parseFile($filename, Yaml::PARSE_CONSTANT);
    }

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
        $resource = $path instanceof SplFileInfo ? $path : new SplFileInfo($path);
        $extension = $resource->getExtension();

        // If configuration file/resource is not readable,
        // stop right here and let the user know.
        if ($resource->isReadable() === false) {
            throw new RuntimeException("Unable to read “{$resource->getPathname()}”.");
        }

        // Resource is a file, try to parse it.
        if ($resource->isFile() && isset($this->parsers[$extension])) {
            $parser = $this->parsers[$extension];
            $append = new static;
            $raw = call_user_func([$this, $parser], $resource->getPathname());

            // Configuration file did not provide any meaningful data…
            if (is_object($raw) === false && is_array($raw) === false) {
                return $this;
            }

            foreach ($raw as $key => $value) {
                $append->set($key, $value);
            }

            $append = $append->all();
            $this->original = array_merge($this->original, $append);
            $this->items = array_merge($this->items, $append);

            $this->notify();

            return $this;
        }

        // Resource is a directory, so try to find any possible configuration file
        // using the set preferred extension.
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
}
