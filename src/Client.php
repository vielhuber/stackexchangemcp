<?php
declare(strict_types=1);

namespace vielhuber\stackexchangemcp;

final class Client
{
    /**
     * Share API backoff across clients without persisting credentials.
     */
    public function __construct(
        #[\SensitiveParameter] private readonly string $apiKey,
        #[\SensitiveParameter] private readonly string $accessToken,
        private readonly string $defaultSite = 'stackoverflow'
    ) {}

    /**
     * Keep vectorized IDs, tags and slugs inside a single path segment.
     */
    public function segment(string $value): string
    {
        if (in_array($value, ['', '.', '..'], strict: true) || strpbrk($value, characters: "/\\\r\n\0") !== false) {
            throw StackExchangeException::failed('Use a nonempty Stack Exchange identifier without path separators.');
        }
        return rawurlencode($value);
    }

    /**
     * Restrict token-management endpoints to the configured account token.
     */
    public function encodedAccessToken(): string
    {
        if ($this->accessToken === '') {
            throw StackExchangeException::failed('Configure STACKEXCHANGE_ACCESS_TOKEN.');
        }
        return rawurlencode($this->accessToken);
    }

    /**
     * Preserve API envelopes while honoring backoff and avoiding write retries.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    public function request(
        string $method,
        #[\SensitiveParameter] string $path,
        string $backoffMethod,
        #[\SensitiveParameter] array $parameters = [],
        ?string $site = null,
        bool $authenticated = false,
        bool $write = false
    ): array {
        if ($authenticated && ($this->accessToken === '' || $this->apiKey === '')) {
            throw StackExchangeException::failed('Configure STACKEXCHANGE_API_KEY and STACKEXCHANGE_ACCESS_TOKEN.');
        }
        if (array_intersect(['key', 'access_token', 'site'], array_keys($parameters)) !== []) {
            throw StackExchangeException::failed('Use configured credentials and the separate site argument.');
        }
        $parameters = array_filter($parameters, static fn($value) => $value !== null);
        if ($site !== null) {
            $parameters['site'] = $site === '' ? $this->defaultSite : $site;
        }
        if ($this->apiKey !== '') {
            $parameters['key'] = $this->apiKey;
        }
        if ($this->accessToken !== '') {
            $parameters['access_token'] = $this->accessToken;
        }
        $parameters = array_map(static function ($value): mixed {
            if (is_array($value)) {
                return implode(';', $value);
            }
            if (is_bool($value)) {
                return $value ? 'true' : 'false';
            }
            return $value;
        }, $parameters);
        $query = http_build_query($parameters, encoding_type: PHP_QUERY_RFC3986);
        $directory =
            sys_get_temp_dir() . '/stackexchangemcp-' . hash('sha256', $this->apiKey . ':' . $this->accessToken);
        if (!is_dir($directory) && !mkdir($directory, permissions: 0o700, recursive: true) && !is_dir($directory)) {
            throw StackExchangeException::failed('Cannot create the private Stack Exchange backoff directory.');
        }
        $lock = fopen($directory . '/lock', mode: 'c+');
        if ($lock === false) {
            throw StackExchangeException::failed('Cannot open the Stack Exchange backoff lock.');
        }
        if (!flock($lock, LOCK_EX)) {
            fclose($lock);
            throw StackExchangeException::failed('Cannot lock Stack Exchange backoff state.');
        }
        try {
            $statePath = $directory . '/' . hash('sha256', $backoffMethod);
            $state = is_file($statePath) ? file_get_contents($statePath) : '0';
            if ($state === false || !ctype_digit($state)) {
                throw StackExchangeException::failed('Stack Exchange backoff state is invalid.');
            }
            if ((int) $state > time()) {
                throw StackExchangeException::failed(
                    'Stack Exchange backoff: retry in ' . ((int) $state - time()) . ' seconds.',
                    429
                );
            }
            try {
                $response = $this->send(
                    method: $method,
                    url: 'https://api.stackexchange.com/2.3' .
                        $path .
                        ($method === 'GET' && $query !== '' ? '?' . $query : ''),
                    body: $method === 'POST' ? $query : null,
                    write: $write
                );
            } catch (StackExchangeException $exception) {
                if ($exception->getCode() === 429 && file_put_contents($statePath, (string) (time() + 60)) === false) {
                    throw StackExchangeException::failed('Cannot persist Stack Exchange backoff.');
                }
                throw $exception;
            }
            if (
                isset($response['backoff']) &&
                file_put_contents($statePath, (string) (time() + (int) $response['backoff'])) === false
            ) {
                throw StackExchangeException::failed('Cannot persist Stack Exchange backoff.');
            }
            if (isset($response['error_id'])) {
                throw StackExchangeException::failed(
                    'Stack Exchange API error ' .
                        (int) $response['error_id'] .
                        '.' .
                        ($write ? ' Read the current state before retrying an uncertain write.' : ''),
                    (int) $response['error_id']
                );
            }
            $credentials = array_values(
                array_filter([$this->apiKey, $this->accessToken], static fn($value) => $value !== '')
            );
            array_walk_recursive($response, static function (&$value) use ($credentials): void {
                if (is_string($value)) {
                    $value = str_replace($credentials, replace: '[redacted]', subject: $value);
                }
            });
            return $response;
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    /**
     * Exchange authorization codes only for PHP callers, not MCP tools.
     *
     * @param array<string, string> $parameters
     * @return array<mixed>
     */
    public function grant(#[\SensitiveParameter] array $parameters): array
    {
        return $this->send(
            method: 'POST',
            url: 'https://stackoverflow.com/oauth/access_token/json',
            body: http_build_query($parameters, encoding_type: PHP_QUERY_RFC3986),
            write: true
        );
    }

    /**
     * Surface transport and API failures without returning response bodies.
     *
     * @return array<mixed>
     */
    private function send(
        string $method,
        #[\SensitiveParameter] string $url,
        #[\SensitiveParameter] ?string $body,
        bool $write
    ): array {
        $handle = curl_init($url);
        curl_setopt_array($handle, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_ENCODING => '',
            CURLOPT_PROTOCOLS_STR => 'https',
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'stackexchangemcp/0.1 (+https://github.com/vielhuber/stackexchangemcp)',
            CURLOPT_HTTPHEADER => ['Accept: application/json', 'Content-Type: application/x-www-form-urlencoded']
        ]);
        if ($body !== null) {
            curl_setopt($handle, CURLOPT_POSTFIELDS, $body);
        }
        $response = curl_exec($handle);
        $status = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        $uncertain = $write ? ' Read the current state before retrying an uncertain write.' : '';
        if ($response === false || $status < 200 || $status >= 300) {
            throw StackExchangeException::failed(
                'Stack Exchange request failed (HTTP ' . $status . ').' . $uncertain,
                $status
            );
        }
        try {
            $decoded = json_decode($response, associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw StackExchangeException::failed('Stack Exchange returned invalid JSON.' . $uncertain);
        }
        if (!is_array($decoded)) {
            throw StackExchangeException::failed('Stack Exchange returned an unexpected response.' . $uncertain);
        }
        return $decoded;
    }
}
