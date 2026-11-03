<?php
declare(strict_types=1);

namespace vielhuber\stackexchangemcp;

final class Authorization
{
    /**
     * Support public PKCE clients and confidential applications with the same grant.
     */
    public function __construct(
        private readonly string $clientId,
        private readonly string $redirectUri,
        #[\SensitiveParameter] private readonly ?string $clientSecret = null
    ) {}

    /**
     * Build a PKCE consent URL; the caller must retain the verifier and verify state.
     *
     * @param list<string> $scopes
     */
    public function url(string $state, string $codeChallenge, array $scopes = []): string
    {
        if ($state === '' || preg_match('/^[a-zA-Z0-9_-]{43}$/D', $codeChallenge) !== 1) {
            throw StackExchangeException::failed('Provide unpredictable OAuth state and a SHA-256 PKCE challenge.');
        }
        return 'https://stackoverflow.com/oauth?' .
            http_build_query(
                [
                    'client_id' => $this->clientId,
                    'redirect_uri' => $this->redirectUri,
                    'response_type' => 'code',
                    'code_challenge' => $codeChallenge,
                    'code_challenge_method' => 'S256',
                    'scope' => implode(' ', $scopes),
                    'state' => $state
                ],
                encoding_type: PHP_QUERY_RFC3986
            );
    }

    /**
     * Obtain credentials for private storage rather than exposing a token tool.
     *
     * @return array<mixed>
     */
    public function exchangeCode(
        #[\SensitiveParameter] string $code,
        #[\SensitiveParameter] string $codeVerifier
    ): array {
        if (preg_match('/^[a-zA-Z0-9._~-]{43,128}$/D', $codeVerifier) !== 1) {
            throw StackExchangeException::failed('Provide the original PKCE verifier.');
        }
        $parameters = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'code' => $code,
            'code_verifier' => $codeVerifier
        ];
        if ($this->clientSecret !== null) {
            $parameters['client_secret'] = $this->clientSecret;
        }
        return new Client('', '')->grant($parameters);
    }
}
