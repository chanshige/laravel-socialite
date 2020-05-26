<?php
declare(strict_types=1);

namespace Chanshige\Laravel\Socialite;

use Chanshige\Laravel\Socialite\Two\SlackProvider;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\SocialiteManager as BaseSocialiteManager;

/**
 * Class SocialiteManager
 *
 * @package Chanshige\Laravel\Socialite
 */
class SocialiteManager extends BaseSocialiteManager
{
    /**
     * Create an instance of the specified driver.
     *
     * @return AbstractProvider
     */
    protected function createSlackDriver()
    {
        return $this->buildProvider(
            SlackProvider::class,
            $this->container['config']['services.slack']
        );
    }
}
