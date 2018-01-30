<?php

namespace µ;

use FastRoute\DataGenerator\GroupCountBased as DataGenerator;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std as RouteParser;
use LogicException;
use RuntimeException;

/**
 * Provides access to a simple wrapper for FastRoute.
 *
 * @return RouteCollector
 * @see https://github.com/nikic/FastRoute
 */
function router(): RouteCollector {
    static $router;

    if ($router instanceof RouteCollector === false) {
        $router = new class(
            new RouteParser,
            new DataGenerator
        ) extends RouteCollector {
            private function handle($statusCode, $handler = null, $data = []) {
                if ($statusCode === Dispatcher::NOT_FOUND) {
                    return [404];
                }

                if ($statusCode === Dispatcher::METHOD_NOT_ALLOWED) {
                    return [405, $handler];
                }

                call_user_func_array($handler, $data);

                return [200];
            }

            public function dispatch(string $httpMethod = null, string $uri = null) {
                $httpMethod = $httpMethod ?? $_SERVER['REQUEST_METHOD'];
                $uri = rawurldecode(strtok($uri ?? $_SERVER['REQUEST_URI'],'?'));
                $data = $this->getData();
                $useCache = config()->get('router.cache', false);

                if ($useCache === false) {
                    return $this->handle(
                        ...(new Dispatcher($data))->dispatch($httpMethod, $uri)
                    );
                }

                $cacheFile = config()->get('router.cacheFile');

                if (isset($cacheFile) === false) {
                    throw new LogicException('Must specify “router.cacheFile” option when caching is enabled.');
                }

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
        };
    }

    return $router;
};
