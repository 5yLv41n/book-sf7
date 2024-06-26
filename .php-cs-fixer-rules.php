<?php

return [
    '@Symfony' => true,
    'lowercase_keywords' => true,
    'self_static_accessor' => true,
    'header_comment' => ['header' => ''],
    'multiline_comment_opening_closing' => true,
    'no_superfluous_elseif' => true,
    'no_useless_else' => true,
    'simplified_if_return' => true,
    'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'parameters']],
    'nullable_type_declaration_for_default_null_value' => true,
    'global_namespace_import' => true,
    'combine_consecutive_issets' => true,
    'combine_consecutive_unsets' => true,
    'explicit_indirect_variable' => true,
    'list_syntax' => true,
    'operator_linebreak' => true,
    'ternary_to_null_coalescing' => true,
    'align_multiline_comment' => true,
    'general_phpdoc_annotation_remove' => true,
    'phpdoc_add_missing_param_annotation' => ['only_untyped' => true],
    'phpdoc_line_span' => ['property' => 'single', 'method' => 'multi', 'const' => 'single'],
    'phpdoc_no_empty_return' => true,
    'phpdoc_order' => true,
    'phpdoc_tag_casing' => true,
    'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
    'phpdoc_var_annotation_correct_order' => true,
    'phpdoc_scalar' => ['types' => ['boolean', 'double', 'integer', 'real', 'str']],
    'no_useless_return' => true,
    'return_assignment' => true,
    'multiline_whitespace_before_semicolons' => true,
    'explicit_string_variable' => true,
    'heredoc_to_nowdoc' => true,
    'simple_to_complex_string_variable' => true,
    'array_indentation' => true,
    'heredoc_indentation' => true,
    'method_chaining_indentation' => true,
];
