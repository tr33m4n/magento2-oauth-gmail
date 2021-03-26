<?php

use Rector\Core\Configuration\Option;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) : void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/src']);
    $parameters->set(Option::SETS, [
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::CODE_QUALITY,
        SetList::PHP_73
    ]);

    $parameters->set(Option::SKIP, [
        RemoveUselessParamTagRector::class,
        RemoveUselessReturnTagRector::class
    ]);

    $services = $containerConfigurator->services();
    $services->set(TypedPropertyRector::class);
};
