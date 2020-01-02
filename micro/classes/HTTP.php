<?php

declare(strict_types=1);

namespace µ;

use InvalidArgumentException;

class HTTP
{
    /**
     * Collect data from a get request in correct order.
     *
     * @param array $keys Array of keys to get.
     * @return array
     */
    public function get(array $keys): array
    {
        return $this->getFromPayload($keys, $_GET ?? []);
    }

    /**
     * Collect data from a post request in correct order.
     *
     * @param array $keys Array of keys to get.
     * @return array
     */
    public function post(array $keys): array
    {
        return $this->getFromPayload($keys, $_POST ?? []);
    }

    /**
     * Collect data from the cookies in correct order.
     *
     * @param array $keys Array of keys to get.
     * @return array
     */
    public function cookie(array $keys): array
    {
        return $this->getFromPayload($keys, $_COOKIE ?? []);
    }

    /**
     * Collect data from a put request in correct order.
     *
     * Keep in mind: HTTP semantics has it that PUT on a resource should
     * replace the resource with the representation sent in the request.
     * The resource is expected to have a equivalent, if not bitwise equal,
     * representation as the request.
     *
     * @see https://github.com/symfony/symfony/pull/10381
     * @see https://bugs.php.net/bug.php?id=55815
     *
     * @param array $keys Array of keys to get.
     * @return array
     */
    public function put(array $keys): array
    {
        parse_str(file_get_contents('php://input'), $_PUT);

        return $this->getFromPayload($keys, $_PUT ?? []);
    }

    /**
     * Collects data from the request payload in correct order.
     *
     * @param array $keys Array of keys to get.
     * @param array $payload Payload to retrieve data from.
     * @return array
     */
    public function getFromPayload(array $keys, array $payload = []): array
    {
        $result = [];

        foreach ($keys as $key) {
            if (is_string($key) === false) {
                throw new InvalidArgumentException(
                    sprintf(
                        '%s()’s second argument “$keys” is expected to be an array of strings. Found “%s” instead.',
                        __METHOD__,
                        gettype($key)
                    )
                );
            }

            // Match pattern:
            // (\w+)    Key name
            // (?::     Starting indicator for data type. Optional.
            //   (\w+)  Type name
            // )?       Should only match once
            if (preg_match('/(\w+)(?::(\w+))?/', $key, $matches) !== 1) {
                continue;
            }

            $key = $matches[1];
            $type = $matches[2] ?? 'string';
            $value = $payload[$key] ?? null;

            if ($type === 'bool') {
                $result[] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } elseif ($type === 'array') {
                $result[] = (array)$value;
            } elseif ($type === 'float') {
                $result[] = floatval($value);
            } elseif ($type === 'int') {
                $result[] = intval($value);
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * Returns true if the script was queried through the HTTPS protocol.
     *
     * @return bool
     */
    public function isSecure(): bool
    {
        return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    }

    /**
     * Returns the protocol of the current request.
     *
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->isSecure() === true ? 'https' : 'http';
    }

    /**
     * Returns the “Host” header from the current request, if there is one.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $_SERVER['HTTP_HOST'] ?? '';
    }

    /**
     * Returns the path part of the current request.
     *
     * @param null $uri Uri to retrieve path from.
     * @return string
     */
    public function getPath($uri = null): string
    {
        return rawurldecode(strtok($uri ?? $_SERVER['REQUEST_URI'] ?? '', '?'));
    }

    /**
     * Returns the url of the current request without query parameters.
     *
     * @return string
     */
    public function getURL(): string
    {
        return $this->getProtocol() . '://' . $this->getHost() . $this->getPath();
    }

    /**
     * Returns the method of the current request.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Returns the user agent of the current request.
     *
     * @param string $fallback Fallback value to return if user agent wasn’t found.
     * @return string
     */
    public function getUserAgent(string $fallback = 'Unknown'): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? $fallback;
    }

    /**
     * Returns the IP address of the current request.
     *
     * @param string $fallback Fallback value to return if IP address wasn’t found.
     * @return string
     */
    public function getUserIp(string $fallback = 'Unknown'): string
    {
        return
            $_SERVER['HTTP_CLIENT_IP'] ??
            $_SERVER['HTTP_X_FORWARDED_FOR'] ??
            $_SERVER['REMOTE_ADDR'] ??
            $fallback;
    }
}
