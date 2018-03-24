<?php
declare(strict_types=1);

namespace CmdrSharp\GuzzleWrapper;

use Illuminate\Support\ServiceProvider;

class GuzzleWrapperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.;
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ClientInterface::class, Client::class);
    }
}
