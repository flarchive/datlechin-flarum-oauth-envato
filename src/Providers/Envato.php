<?php

namespace Datlechin\OAuthEnvato\Providers;

use Flarum\Forum\Auth\Registration;
use FoF\OAuth\Provider;
use League\OAuth2\Client\Provider\AbstractProvider;

class Envato extends Provider
{
    public function name(): string
    {
        return 'envato';
    }

    public function link(): string
    {
        return 'https://build.envato.com/my-apps/';
    }

    public function icon(): string
    {
        return 'fas fa-store';
    }

    public function fields(): array
    {
        return [
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
        ];
    }

    public function provider(string $redirectUrl): AbstractProvider
    {
        return new EnvatoProvider([
            'clientId' => $this->getSetting('client_id'),
            'clientSecret' => $this->getSetting('client_secret'),
            'redirectUri' => $redirectUrl,
        ]);
    }

    /**
     * @param EnvatoResourceOwner $user
     */
    public function suggestions(Registration $registration, $user, string $token): void
    {
        $registration
            ->suggestUsername($user->getUsername())
            ->setPayload($user->toArray())
            ->suggestEmail($user->getEmail())
            ->provideAvatar($user->getImageUrl());
    }
}
