<?php

declare(strict_types=1);

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Fixer\Phpdoc\ForceFQCNFixer;

$finder = PhpCsFixer\Finder::create()->in(__DIR__ . '/src');

$config = new PhpCsFixer\Config();
$config->setUsingCache(false);
$config->registerCustomFixers([new ForceFQCNFixer()]);

return $config->setRules([
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'concat_space' => ['spacing' => 'one'],
    'include' => true,
    'new_with_braces' => true,
    'no_empty_statement' => true,
    'no_leading_import_slash' => true,
    'no_leading_namespace_whitespace' => true,
    'no_multiline_whitespace_around_double_arrow' => true,
    'multiline_whitespace_before_semicolons' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_trailing_comma_in_singleline_array' => true,
    'no_unused_imports' => true,
    'no_whitespace_in_blank_line' => true,
    'object_operator_without_whitespace' => true,
    'ordered_imports' => true,
    'standardize_not_equals' => true,
    'ternary_operator_spaces' => true,
    'AdamWojs/phpdoc_force_fqcn_fixer' => true
])->setFinder($finder);
