<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

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
    ->withTypeCoverageLevel(10)
    ->withDeadCodeLevel(10)
    ->withCodeQualityLevel(10)
    ->withCodingStyleLevel(10)
    ->withComposerBased(laravel: true)
    ->withRules([
        \RectorLaravel\Rector\ClassMethod\AddGenericReturnTypeToRelationsRector::class,
        \RectorLaravel\Rector\Expr\AppEnvironmentComparisonToParameterRector::class,
        \RectorLaravel\Rector\MethodCall\ChangeQueryWhereDateValueWithCarbonRector::class,
        \RectorLaravel\Rector\Empty_\EmptyToBlankAndFilledFuncRector::class,
        \RectorLaravel\Rector\PropertyFetch\OptionalToNullsafeOperatorRector::class,
        \RectorLaravel\Rector\MethodCall\RedirectBackToBackHelperRector::class,
        \RectorLaravel\Rector\FuncCall\RemoveDumpDataDeadCodeRector::class,
        \RectorLaravel\Rector\Class_\RemoveModelPropertyFromFactoriesRector::class,
        \RectorLaravel\Rector\FuncCall\RemoveRedundantValueCallsRector::class,
        \RectorLaravel\Rector\PropertyFetch\ReplaceFakerInstanceWithHelperRector::class,
        \RectorLaravel\Rector\MethodCall\ResponseHelperCallToJsonResponseRector::class,
        \RectorLaravel\Rector\ClassMethod\ScopeNamedClassMethodToScopeAttributedClassMethodRector::class,
        \RectorLaravel\Rector\FuncCall\TypeHintTappableCallRector::class,
        \RectorLaravel\Rector\MethodCall\UnaliasCollectionMethodsRector::class,
        \RectorLaravel\Rector\MethodCall\UseComponentPropertyWithinCommandsRector::class,
        \RectorLaravel\Rector\MethodCall\ValidationRuleArrayStringValueToArrayRector::class,
        \RectorLaravel\Rector\MethodCall\RedirectRouteToToRouteHelperRector::class,
        \RectorLaravel\Rector\Class_\ModelCastsPropertyToCastsMethodRector::class,

        \Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector::class,
        \Rector\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector::class,
        \Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector::class,
    ]);
