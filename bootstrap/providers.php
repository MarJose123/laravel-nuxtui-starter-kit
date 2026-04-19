<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\SanctumServiceProvider;
use App\Providers\ScrambleServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    SanctumServiceProvider::class,
    ScrambleServiceProvider::class,
];
