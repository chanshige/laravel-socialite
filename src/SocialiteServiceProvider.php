<?php

namespace Chanshige\Laravel\Socialite;

use Laravel\Socialite\Contracts\Factory;
use Laravel\Socialite\SocialiteServiceProvider as BaseSocialiteServiceProvider;

/**
 * Class SocialiteServiceProvider
 *
 * @package Chanshige\Laravel\Socialite
 */
class SocialiteServiceProvider extends BaseSocialiteServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->app->singleton(Factory::class, function ($app) {
            return new SocialiteManager($app);
        });
    }
}
