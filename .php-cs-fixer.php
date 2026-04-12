<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setUsingCache(true)
    ->setRules([
        '@PSR12'                            => true,
        '@PHP82Migration'                   => true,
        'ordered_imports'                   => ['sort_algorithm' => 'alpha'],
        'no_unused_imports'                 => true,
        'not_operator_with_successor_space' => true,
        'trailing_comma_in_multiline'       => true,
        'blank_line_before_statement'       => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'declare_strict_types'              => true,
        'no_extra_blank_lines'              => true,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true);
