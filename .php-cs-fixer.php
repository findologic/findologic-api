<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$config = new Config();

$finder = Finder::create()->in(['src', 'tests']);
$config->setFinder($finder);

$rules = [
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
    'constant_case' => true,
    'declare_strict_types' => true,
    'single_quote' => true,
    'strict_param' => true,
];
$config->setRules($rules);

return $config;
