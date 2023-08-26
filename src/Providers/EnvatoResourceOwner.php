<?php

namespace Datlechin\OAuthEnvato\Providers;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class EnvatoResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId(): ?string
    {
        return $this->getValueByKey($this->response, 'id');
    }

    public function getEmail(): string
    {
        return $this->getValueByKey($this->response, 'email');
    }

    public function getName(): string
    {
        return sprintf('%s %s', $this->getFirstName(), $this->getLastName());
    }

    public function getImageUrl(): string
    {
        return $this->getValueByKey($this->response, 'account.image');
    }

    public function getFirstName(): string
    {
        return $this->getValueByKey($this->response, 'firstname');
    }

    public function getLastName(): string
    {
        return $this->getValueByKey($this->response, 'surname');
    }

    public function getUsername(): string
    {
        return $this->getValueByKey($this->response, 'username');
    }

    public function toArray(): array
    {
        return $this->response;
    }
}
