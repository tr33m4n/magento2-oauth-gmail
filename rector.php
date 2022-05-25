<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->import(SetList::DEAD_CODE);
    $rectorConfig->import(SetList::EARLY_RETURN);
    $rectorConfig->import(SetList::CODE_QUALITY);
    $rectorConfig->import(SetList::TYPE_DECLARATION);
    $rectorConfig->import(SetList::PHP_74);

    $rectorConfig->paths([__DIR__ . '/src']);
    $rectorConfig->skip([
        RemoveUselessParamTagRector::class,
        RemoveUselessReturnTagRector::class,
        __DIR__ . '/src/Test/*'
    ]);
};
