<?php

namespace Datlechin\OAuthEnvato\Providers;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class EnvatoProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected string $envatoUrl = 'https://api.envato.com';

    public function getBaseAuthorizationUrl(): string
    {
        return $this->envatoUrl . '/authorization';
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->envatoUrl . '/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->envatoUrl . '/v1/market/private/user/account.json';
    }

    public function getResourceOwnerEmailUrl(AccessToken $token): string
    {
        return $this->envatoUrl . '/v1/market/private/user/email.json';
    }

    public function getResourceOwnerUsernameUrl(AccessToken $token): string
    {
        return $this->envatoUrl . '/v1/market/private/user/username.json';
    }

    public function getResourceOwnerWhoAmIUrl(AccessToken $token): string
    {
        return $this->envatoUrl . '/whoami';
    }

    protected function getDefaultScopes(): array
    {
        return [];
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() >= 400) {
            throw new IdentityProviderException(
                sprintf('Error retrieving token: %s', $response->getReasonPhrase()),
                $response->getStatusCode(),
                $response
            );
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): EnvatoResourceOwner
    {
        try {
            $email = $this->getResourceOwnerEmail($token);
        } catch (IdentityProviderException $e) {
            $email = null;
        }

        try {
            $username = $this->getResourceOwnerUsername($token);
        } catch (IdentityProviderException $e) {
            $username = null;
        }

        try {
            $id = $this->getResourceOwnerWhoAmI($token);
        } catch (IdentityProviderException $e) {
            $id = null;
        }

        $response['email'] = $email;
        $response['username'] = $username;
        $response['id'] = $id;

        return new EnvatoResourceOwner($response);
    }

    public function getResourceOwnerEmail(AccessToken $token): string
    {
        $request = $this->getAuthenticatedRequest(self::METHOD_GET, $this->getResourceOwnerEmailUrl($token), $token);
        $response = $this->getParsedResponse($request);

        return $response['email'];
    }

    public function getResourceOwnerUsername(AccessToken $token): string
    {
        $request = $this->getAuthenticatedRequest(self::METHOD_GET, $this->getResourceOwnerUsernameUrl($token), $token);
        $response = $this->getParsedResponse($request);

        return $response['username'];
    }

    public function getResourceOwnerWhoAmI(AccessToken $token): string
    {
        $request = $this->getAuthenticatedRequest(self::METHOD_GET, $this->getResourceOwnerWhoAmIUrl($token), $token);
        $response = $this->getParsedResponse($request);

        return $response['userId'];
    }

    public function getAccessToken($grant, array $options = []): AccessTokenInterface
    {
        return parent::getAccessToken($grant, $options);
    }
}
