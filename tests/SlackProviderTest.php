<?php

namespace Chanshige\Laravel\Socialite;

use Chanshige\Laravel\Socialite\Two\SlackProvider;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\User;
use PHPUnit\Framework\TestCase;
use Mockery as M;
use Psr\Http\Message\ResponseInterface;

use function GuzzleHttp\json_encode;

/**
 * Class SlackProviderTest
 *
 * @package Chanshige\Laravel\Socialite
 */
class SlackProviderTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    public function testProvider()
    {
        $request = M::mock(Request::class);
        $request->shouldReceive('input')->with('code')->andReturn('fake-code');

        $accessTokenResponse = M::mock(ResponseInterface::class);
        $accessTokenResponse->shouldReceive('getBody')->andReturn(json_encode(
            [
                'access_token' => $token = 'fake-token'
            ]
        ));

        $userResponse = M::mock(ResponseInterface::class);
        $userResponse->shouldReceive('getBody')->andReturn(json_encode(
            [
                'user' => [
                    'id' => $userId = 'user000',
                    'name' => $name = 'slack user',
                    'email' => $email = 'slack@test.example',
                    'image_512' => $image = 'https://image.example/image.path',

                ],
                'team' => [
                    'id' => $teamId = 'org123'
                ]
            ]
        ));

        $guzzle = M::mock(Client::class);
        $guzzle->shouldReceive('post')->once()->andReturn($accessTokenResponse);
        $guzzle->shouldReceive('get')->with(
            'https://slack.com/api/users.identity?token=' . $token,
            [
                'headers' => [
                    'Accept' => 'application/x-www-form-urlencoded',
                ]
            ]
        )->andReturn($userResponse);

        $provider = new SlackProvider($request, 'client_id', 'client_secret', 'redirect');
        $provider->stateless();
        $provider->setHttpClient($guzzle);

        $user = $provider->user();

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame($userId, $user->getId());
        $this->assertSame($name, $user->getName());
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($image, $user->getAvatar());
    }
}
