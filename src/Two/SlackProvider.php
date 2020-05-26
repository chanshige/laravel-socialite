<?php
declare(strict_types=1);

namespace Chanshige\Laravel\Socialite\Two;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

use function GuzzleHttp\json_decode;

/**
 * Class SlackProvider
 *
 * @package App\Services\Socialite
 */
class SlackProvider extends AbstractProvider implements ProviderInterface
{
    /** @var array */
    protected $scopes = [
        'identity.basic',
        'identity.team',
        'identity.email',
        'identity.avatar'
    ];

    /**
     * {@inheritDoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://slack.com/oauth/authorize', $state);
    }

    /**
     * {@inheritDoc}
     */
    protected function getTokenUrl()
    {
        return 'https://slack.com/api/oauth.access';
    }

    /**
     * {@inheritDoc}
     */
    protected function getUserByToken($token)
    {
        //@see https://api.slack.com/methods/users.identity
        $endpoint = 'https://slack.com/api/users.identity?' . http_build_query(['token' => $token]);
        $response = $this->getHttpClient()->get(
            $endpoint,
            $this->requestOptions()
        );

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * {@inheritDoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'user.id'),
            'name' => Arr::get($user, 'user.name'),
            'email' => Arr::get($user, 'user.email'),
            'avatar' => Arr::get($user, 'user.image_512'),
            'organization_id' => Arr::get($user, 'team.id'),
        ]);
    }

    /**
     * Get the default options for an HTTP request.
     *
     * @return array
     */
    protected function requestOptions()
    {
        return [
            'headers' => [
                'Accept' => 'application/x-www-form-urlencoded',
            ],
        ];
    }
}
