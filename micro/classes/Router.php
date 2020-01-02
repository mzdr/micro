<?php

declare(strict_types=1);

namespace µ;

use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\RouteCollector;
use LogicException;
use RuntimeException;

class Router extends RouteCollector
{

    /**
     * Handles the result of the Dispatcher.
     *
     * @param int $statusCode Response status code.
     * @param null $handler Either array of allowed methods or the route handling function.
     * @param array $data The data to pass to the route handling function.
     * @return array
     */
    private function handle($statusCode, $handler = null, $data = [])
    {
        if ($statusCode === Dispatcher::NOT_FOUND) {
            return [404];
        }

        if ($statusCode === Dispatcher::METHOD_NOT_ALLOWED) {
            return [405, $handler];
        }

        return [200, call_user_func_array($handler, $data)];
    }

    /**
     * Dispatches a given uri and http method.
     *
     * @param string|null $httpMethod [Optional] Request method.
     * @param string|null $uri [Optional] Request uri.
     * @return array First element always contains status code, second/third depend on first one.
     */
    public function dispatch(string $httpMethod = null, string $uri = null)
    {
        $httpMethod = strtoupper($httpMethod ?? http()->getMethod());
        $uri = http()->getPath($uri);
        $data = $this->getData();
        $useCache = config()->get('µ.router.cache', false);

        if ($useCache === false) {
            return $this->handle(
                ...(new Dispatcher($data))->dispatch($httpMethod, $uri)
            );
        }

        $cacheFile = config()->get('µ.router.cacheFile');

        if (isset($cacheFile) === false) {
            throw new LogicException('Must specify “router.cacheFile” option when caching is enabled.');
        }

        $cacheFile = root()->getPath($cacheFile);

        if (file_exists($cacheFile) === false) {
            file_put_contents(
                $cacheFile,
                sprintf('<?php return %s;', var_export($data, true))
            );
        }

        $data = require $cacheFile;

        if (is_array($data) === false) {
            throw new RuntimeException(sprintf('Invalid cache file “%s”.', $cacheFile));
        }

        return $this->handle(
            ...(new Dispatcher($data))->dispatch($httpMethod, $uri)
        );
    }
}
