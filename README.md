# chanshige\laravel-socialite

laravel/socialite をベースに、OAuthログインサービスを拡張させたもの

# Install
    $ composer config repositories.chanshige/laravel-socialite vcs https://github.com/chanshige/laravel-socialite.git
    $ composer require chanshige/laravel-socialite:^1.0

### added serviceProvider (config/app.php)
    'providers' => [
        Chanshige\Laravel\Socialite\SocialiteServiceProvider::class,
    ]
