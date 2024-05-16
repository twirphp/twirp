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
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/lib')
            ->in(__DIR__ . '/tests/complete/tests')
            ->name('*.php')
    );

return $config;
