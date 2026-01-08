<?php

$config = (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@PHP74Migration' => true,
        '@PHPUnit84Migration:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'yoda_style' => false,
        'modernize_strpos' => false,

        // until PHP 8.0
        'trailing_comma_in_multiline' => false,
        'native_function_invocation' => false,
        'phpdoc_to_comment' => [
            'allow_before_return_statement' => true,
        ]
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/lib')
            ->in(__DIR__ . '/tests/complete/tests')
            ->name('*.php')
    );

return $config;
