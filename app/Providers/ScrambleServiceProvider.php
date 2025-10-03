<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Illuminate\Support\ServiceProvider;

class ScrambleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Scramble::configure()
            ->expose(
                ui: false,
                document: '/docs/v1/openapi.json'
            );

    }
}
