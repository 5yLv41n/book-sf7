<?php

$excludes = [
    'bin',
    'config',
    'migrations',
    'public',
    'resources',
    'templates',
    'var',
    'vendor',
];

$rules = include '.php-cs-fixer-rules.php';

$riskyRules = [
    'array_push' => true,
    'pow_to_exponentiation' => true,
    'random_api_migration' => true,
    'comment_to_phpdoc' => ['ignored_tags' => ['todo']],
    'combine_nested_dirname' => true,
    'static_lambda' => true,
    'php_unit_dedicate_assert' => true,
    'php_unit_dedicate_assert_internal_type' => true,
    'php_unit_expectation' => true,
    'php_unit_mock' => true,
    'php_unit_namespaced' => true,
    'php_unit_no_expectation_annotation' => true,
    'php_unit_test_case_static_method_calls' => true,
    'ereg_to_preg' => true,
    'no_alias_functions' => true,
    'set_type_to_cast' => true,
    'non_printable_character' => true,
    'modernize_types_casting' => true,
    'no_php4_constructor' => true,
    'no_unneeded_final_method' => true,
    'fopen_flag_order' => true,
    'implode_call' => true,
    'no_unreachable_default_argument_value' => true,
    'no_useless_sprintf' => true,
    'dir_constant' => true,
    'function_to_constant' => true,
    'is_null' => true,
    'no_homoglyph_names' => true,
    'logical_operators' => true,
    'ternary_to_elvis_operator' => true,
    'php_unit_construct' => true,
    'php_unit_mock_short_will_return' => true,
    'php_unit_set_up_tear_down_visibility' => true,
    'php_unit_test_annotation' => true,
    'no_unset_on_property' => true,
];

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude($excludes);

return (new PhpCsFixer\Config())
    ->setRules(array_merge($rules, $riskyRules))
    ->setFinder($finder);
