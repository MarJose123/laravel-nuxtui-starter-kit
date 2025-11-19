<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Rector\FuncCall\RemoveDumpDataDeadCodeRector;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/config',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    // Larastan / Phpstan
    ->withBootstrapFiles([
        __DIR__.'/vendor/larastan/larastan/bootstrap.php',
    ])
    ->withPHPStanConfigs([
        __DIR__.'/phpstan.neon',
    ])
    ->withSkip([
        __DIR__.'/bootstrap/cache/*',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets(php84: true)
    ->withImportNames()
    ->withComposerBased(laravel: true)
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_120,
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_COLLECTION,
        LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
        LaravelSetList::LARAVEL_TYPE_DECLARATIONS,
        LaravelSetList::LARAVEL_TESTING,
    ])->withConfiguredRule(RemoveDumpDataDeadCodeRector::class, [
        'dd', 'dump', 'var_dump', 'ray', 'ray_dump', 'ds',
    ]);
