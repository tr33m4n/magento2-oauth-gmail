<?php

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;

return static function (ContainerConfigurator $containerConfigurator) : void {
    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(SetList::EARLY_RETURN);
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::TYPE_DECLARATION);
    $containerConfigurator->import(SetList::PHP_74);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/src']);
    $parameters->set(Option::SKIP, [
        RemoveUselessParamTagRector::class,
        RemoveUselessReturnTagRector::class,
        __DIR__ . '/src/Test/*'
    ]);
};
