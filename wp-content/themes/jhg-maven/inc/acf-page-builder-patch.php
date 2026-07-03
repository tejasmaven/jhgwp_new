<?php

/**
 * Patch Page Builder ACF JSON and optionally sync into the WordPress database.
 *
 * Run JSON patch:
 * php -r "require 'wp-load.php'; require get_template_directory().'/inc/acf-page-builder-patch.php'; jhg_patch_page_builder_acf_json();"
 *
 * Patch JSON + import to DB:
 * php jhg-sync-acf-db.php
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * @param array<int, array<string, mixed>> $sub_fields
 * @param array<int, array<string, mixed>> $to_add
 */
function jhg_acf_add_sub_fields_list(array &$sub_fields, array $to_add): void
{
    $names = array_column($sub_fields, 'name');

    foreach ($to_add as $field) {
        if (! in_array($field['name'], $names, true)) {
            $sub_fields[] = $field;
            $names[]        = $field['name'];
        }
    }
}

/**
 * @param array<int, array<string, mixed>> $sub_fields
 * @param array<string, mixed>             $replacement
 */
function jhg_acf_replace_sub_field_by_name(array &$sub_fields, string $name, array $replacement): void
{
    foreach ($sub_fields as $index => $field) {
        if (($field['name'] ?? '') === $name) {
            $sub_fields[ $index ] = $replacement;

            return;
        }
    }

    $sub_fields[] = $replacement;
}

/**
 * @param array<string, mixed> $existing
 * @param array<string, mixed> $canonical
 *
 * @return array<string, mixed>
 */
function jhg_acf_merge_layout(array $existing, array $canonical, bool $replace_sub_fields = false): array
{
    if ($replace_sub_fields) {
        $existing['sub_fields'] = $canonical['sub_fields'] ?? [];

        return $existing;
    }

    if (! isset($existing['sub_fields'])) {
        $existing['sub_fields'] = [];
    }

    jhg_acf_add_sub_fields_list($existing['sub_fields'], $canonical['sub_fields'] ?? []);

    foreach ($canonical['sub_fields'] ?? [] as $canonical_field) {
        if (($canonical_field['type'] ?? '') !== 'repeater' || empty($canonical_field['sub_fields'])) {
            continue;
        }

        foreach ($existing['sub_fields'] as $index => $existing_field) {
            if (($existing_field['name'] ?? '') !== ($canonical_field['name'] ?? '')) {
                continue;
            }

            if (! isset($existing['sub_fields'][ $index ]['sub_fields'])) {
                $existing['sub_fields'][ $index ]['sub_fields'] = [];
            }

            jhg_acf_add_sub_fields_list(
                $existing['sub_fields'][ $index ]['sub_fields'],
                $canonical_field['sub_fields']
            );
        }
    }

    return $existing;
}

/**
 * @return array<string, array<string, mixed>>
 */
function jhg_page_builder_canonical_layouts(): array
{
    return [
        'layout_text_grid' => [
            'key'        => 'layout_text_grid',
            'name'       => 'text_grid',
            'label'      => 'Text Grid',
            'display'    => 'block',
            'sub_fields' => [
                ['key' => 'field_tg_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text'],
                [
                    'key'          => 'field_tg_items',
                    'label'        => 'Items',
                    'name'         => 'items',
                    'type'         => 'repeater',
                    'layout'       => 'block',
                    'button_label' => 'Add Item',
                    'sub_fields'   => [
                        ['key' => 'field_tg_text', 'label' => 'Text', 'name' => 'text', 'type' => 'textarea', 'rows' => 3],
                    ],
                ],
            ],
        ],
        'layout_comparison_table' => [
            'key'        => 'layout_comparison_table',
            'name'       => 'comparison_table',
            'label'      => 'Comparison Table',
            'display'    => 'block',
            'sub_fields' => [
                ['key' => 'field_ct_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text'],
                ['key' => 'field_ct_intro', 'label' => 'Intro', 'name' => 'intro', 'type' => 'textarea', 'rows' => 2],
                [
                    'key'          => 'field_ct_rows',
                    'label'        => 'Rows',
                    'name'         => 'rows',
                    'type'         => 'repeater',
                    'layout'       => 'table',
                    'button_label' => 'Add Row',
                    'sub_fields'   => [
                        ['key' => 'field_ct_label', 'label' => 'Feature', 'name' => 'label', 'type' => 'text'],
                        ['key' => 'field_ct_jhg', 'label' => 'JHG', 'name' => 'jhg', 'type' => 'text', 'instructions' => 'Use YES/NO for icons, or any text.'],
                        ['key' => 'field_ct_trad', 'label' => 'Traditional HR', 'name' => 'traditional', 'type' => 'text', 'instructions' => 'Use YES/NO for icons, or any text.'],
                    ],
                ],
            ],
        ],
        'layout_trust_metrics' => [
            'key'        => 'layout_trust_metrics',
            'name'       => 'trust_metrics',
            'label'      => 'Trust Metrics',
            'display'    => 'block',
            'sub_fields' => [
                ['key' => 'field_tm_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text'],
                [
                    'key'          => 'field_tm_metrics',
                    'label'        => 'Metrics',
                    'name'         => 'metrics',
                    'type'         => 'repeater',
                    'layout'       => 'table',
                    'button_label' => 'Add Metric',
                    'sub_fields'   => [
                        ['key' => 'field_tm_value', 'label' => 'Value', 'name' => 'value', 'type' => 'text', 'instructions' => 'e.g. 33+'],
                        ['key' => 'field_tm_label', 'label' => 'Label', 'name' => 'label', 'type' => 'textarea', 'rows' => 2, 'instructions' => 'Line breaks become two lines.'],
                    ],
                ],
            ],
        ],
        'layout_pricing_plans' => [
            'key'        => 'layout_pricing_plans',
            'name'       => 'pricing_plans',
            'label'      => 'Pricing Plans',
            'display'    => 'block',
            'sub_fields' => [
                ['key' => 'field_pp_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text'],
                ['key' => 'field_pp_toggle_label', 'label' => 'Toggle label', 'name' => 'toggle_label', 'type' => 'text', 'default_value' => 'Membership Options'],
                ['key' => 'field_pp_promo_text', 'label' => 'Promo text', 'name' => 'promo_text', 'type' => 'text', 'default_value' => '10% off', 'instructions' => 'Shown beside the billing toggle (e.g. annual discount).'],
                [
                    'key'           => 'field_pp_default_period',
                    'label'         => 'Default billing tab',
                    'name'          => 'default_period',
                    'type'          => 'select',
                    'choices'       => ['monthly' => 'Monthly', 'annual' => 'Annual', 'custom' => 'Custom'],
                    'default_value' => 'annual',
                ],
                [
                    'key'          => 'field_pp_plans',
                    'label'        => 'Plans',
                    'name'         => 'plans',
                    'type'         => 'repeater',
                    'layout'       => 'block',
                    'button_label' => 'Add Plan',
                    'sub_fields'   => [
                        ['key' => 'field_pp_billing_period', 'label' => 'Billing period', 'name' => 'billing_period', 'type' => 'select', 'choices' => ['monthly' => 'Monthly', 'annual' => 'Annual', 'custom' => 'Custom'], 'default_value' => 'annual'],
                        ['key' => 'field_pp_name', 'label' => 'Plan name', 'name' => 'name', 'type' => 'text'],
                        ['key' => 'field_pp_price', 'label' => 'Price label', 'name' => 'price', 'type' => 'text', 'instructions' => 'e.g. R5345/annually or Custom Pricing'],
                        ['key' => 'field_pp_featured', 'label' => 'Featured (highlight)', 'name' => 'featured', 'type' => 'true_false', 'ui' => 1],
                        ['key' => 'field_pp_accent', 'label' => 'Accent', 'name' => 'accent', 'type' => 'select', 'choices' => ['navy' => 'Navy', 'red' => 'Red'], 'default_value' => 'navy'],
                        [
                            'key'          => 'field_pp_features',
                            'label'        => 'Features',
                            'name'         => 'features',
                            'type'         => 'repeater',
                            'layout'       => 'table',
                            'button_label' => 'Add feature',
                            'sub_fields'   => [
                                ['key' => 'field_pp_feature', 'label' => 'Feature', 'name' => 'feature', 'type' => 'text'],
                            ],
                        ],
                        ['key' => 'field_pp_btn_text', 'label' => 'Button text', 'name' => 'button_text', 'type' => 'text'],
                        ['key' => 'field_pp_btn_url', 'label' => 'Button URL', 'name' => 'button_url', 'type' => 'url'],
                    ],
                ],
            ],
        ],
        'layout_gradient_checklist' => [
            'key'        => 'layout_gradient_checklist',
            'name'       => 'gradient_checklist',
            'label'      => 'Gradient Checklist',
            'display'    => 'block',
            'sub_fields' => [
                ['key' => 'field_gc_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text'],
                [
                    'key'           => 'field_gc_column_layout',
                    'label'         => 'Layout',
                    'name'          => 'column_layout',
                    'type'          => 'button_group',
                    'choices'       => [
                        'two_column'    => '2 columns',
                        'single_column' => 'Single column',
                    ],
                    'default_value' => 'two_column',
                    'layout'        => 'horizontal',
                ],
                [
                    'key'               => 'field_gc_checklist_left',
                    'label'             => 'Left column',
                    'name'              => 'checklist_left',
                    'type'              => 'repeater',
                    'layout'            => 'table',
                    'button_label'      => 'Add item',
                    'wrapper'           => ['width' => '50'],
                    'conditional_logic' => [
                        [
                            [
                                'field'    => 'field_gc_column_layout',
                                'operator' => '==',
                                'value'    => 'two_column',
                            ],
                        ],
                    ],
                    'sub_fields'        => [
                        ['key' => 'field_gc_checklist_left_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text'],
                    ],
                ],
                [
                    'key'               => 'field_gc_checklist_right',
                    'label'             => 'Right column',
                    'name'              => 'checklist_right',
                    'type'              => 'repeater',
                    'layout'            => 'table',
                    'button_label'      => 'Add item',
                    'wrapper'           => ['width' => '50'],
                    'conditional_logic' => [
                        [
                            [
                                'field'    => 'field_gc_column_layout',
                                'operator' => '==',
                                'value'    => 'two_column',
                            ],
                        ],
                    ],
                    'sub_fields'        => [
                        ['key' => 'field_gc_checklist_right_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text'],
                    ],
                ],
                [
                    'key'               => 'field_gc_checklist',
                    'label'             => 'Checklist',
                    'name'              => 'checklist',
                    'type'              => 'repeater',
                    'layout'            => 'table',
                    'button_label'      => 'Add item',
                    'conditional_logic' => [
                        [
                            [
                                'field'    => 'field_gc_column_layout',
                                'operator' => '==',
                                'value'    => 'single_column',
                            ],
                        ],
                    ],
                    'sub_fields'        => [
                        ['key' => 'field_gc_check_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text'],
                    ],
                ],
            ],
        ],
        'layout_checklist_cards' => [
            'key'        => 'layout_checklist_cards',
            'name'       => 'checklist_cards',
            'label'      => 'Checklist Cards',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_clc_column_layout',
                    'label'         => 'Layout',
                    'name'          => 'column_layout',
                    'type'          => 'button_group',
                    'choices'       => [
                        'two_column'    => '2 columns',
                        'single_column' => 'Single column',
                    ],
                    'default_value' => 'two_column',
                    'layout'        => 'horizontal',
                ],
                [
                    'key'          => 'field_clc_cards',
                    'label'        => 'Cards',
                    'name'         => 'cards',
                    'type'         => 'repeater',
                    'layout'       => 'block',
                    'button_label' => 'Add card',
                    'min'          => 1,
                    'max'          => 2,
                    'instructions' => 'Use one card for single-column layout, two cards for side-by-side layout.',
                    'sub_fields'   => [
                        ['key' => 'field_clc_card_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text'],
                        [
                            'key'          => 'field_clc_card_items',
                            'label'        => 'Checklist',
                            'name'         => 'items',
                            'type'         => 'repeater',
                            'layout'       => 'table',
                            'button_label' => 'Add item',
                            'sub_fields'   => [
                                ['key' => 'field_clc_card_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text'],
                            ],
                        ],
                        ['key' => 'field_clc_card_btn_text', 'label' => 'Button text', 'name' => 'button_text', 'type' => 'text'],
                        ['key' => 'field_clc_card_btn_url', 'label' => 'Button URL', 'name' => 'button_url', 'type' => 'url'],
                    ],
                ],
            ],
        ],
        'layout_consultant_cta' => [
            'key'        => 'layout_consultant_cta',
            'name'       => 'consultant_cta',
            'label'      => 'Consultant CTA',
            'display'    => 'block',
            'sub_fields' => [
                ['key' => 'field_cc_heading', 'label' => 'Heading (line 1)', 'name' => 'heading', 'type' => 'text'],
                ['key' => 'field_cc_subheading', 'label' => 'Heading (line 2)', 'name' => 'subheading', 'type' => 'text'],
                ['key' => 'field_cc_btn_text', 'label' => 'Button text', 'name' => 'button_text', 'type' => 'text'],
                ['key' => 'field_cc_btn_url', 'label' => 'Button URL', 'name' => 'button_url', 'type' => 'url'],
            ],
        ],
        'layout_gradient_intro' => [
            'key'        => 'layout_gradient_intro',
            'name'       => 'gradient_intro',
            'label'      => 'Gradient Intro',
            'display'    => 'block',
            'sub_fields' => [
                ['key' => 'field_gi_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text'],
                ['key' => 'field_gi_text_primary', 'label' => 'Primary Text', 'name' => 'text_primary', 'type' => 'textarea', 'rows' => 4],
                ['key' => 'field_gi_text_secondary', 'label' => 'Secondary Text', 'name' => 'text_secondary', 'type' => 'textarea', 'rows' => 4],
            ],
        ],
        'layout_audience_band' => [
            'key'        => 'layout_audience_band',
            'name'       => 'audience_band',
            'label'      => 'Audience Band',
            'display'    => 'block',
            'sub_fields' => [
                ['key' => 'field_ab_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Who Should Attend'],
                ['key' => 'field_ab_line_one', 'label' => 'Audience line 1', 'name' => 'line_one', 'type' => 'textarea', 'rows' => 2, 'instructions' => 'Use • between items.'],
                ['key' => 'field_ab_line_two', 'label' => 'Audience line 2', 'name' => 'line_two', 'type' => 'textarea', 'rows' => 2, 'instructions' => 'Optional second line below the first.'],
            ],
        ],
        'layout_receive_book' => [
            'key'        => 'layout_receive_book',
            'name'       => 'receive_book',
            'label'      => 'Receive & Book',
            'display'    => 'block',
            'sub_fields' => [
                ['key' => 'field_rb_receive_heading', 'label' => 'Receive heading', 'name' => 'receive_heading', 'type' => 'text', 'default_value' => "What You'll Receive"],
                [
                    'key'          => 'field_rb_receive_items',
                    'label'        => "What you'll receive",
                    'name'         => 'receive_items',
                    'type'         => 'repeater',
                    'layout'       => 'table',
                    'button_label' => 'Add item',
                    'sub_fields'   => [
                        ['key' => 'field_rb_receive_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text'],
                    ],
                ],
                ['key' => 'field_rb_book_heading', 'label' => 'Book heading', 'name' => 'book_heading', 'type' => 'text', 'default_value' => 'How to Book'],
                [
                    'key'          => 'field_rb_book_items',
                    'label'        => 'How to book',
                    'name'         => 'book_items',
                    'type'         => 'repeater',
                    'layout'       => 'table',
                    'button_label' => 'Add step',
                    'sub_fields'   => [
                        ['key' => 'field_rb_book_item', 'label' => 'Step', 'name' => 'item', 'type' => 'text'],
                    ],
                ],
                ['key' => 'field_rb_image', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'],
                [
                    'key'           => 'field_rb_media_position',
                    'label'         => 'Image position',
                    'name'          => 'media_position',
                    'type'          => 'select',
                    'choices'       => ['left' => 'Left', 'right' => 'Right'],
                    'default_value' => 'right',
                ],
            ],
        ],
        'layout_workshop_cards' => [
            'key'        => 'layout_workshop_cards',
            'name'       => 'workshop_cards',
            'label'      => 'Workshop Cards',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'          => 'field_wc_workshops',
                    'label'        => 'Workshops',
                    'name'         => 'workshops',
                    'type'         => 'repeater',
                    'layout'       => 'block',
                    'button_label' => 'Add workshop',
                    'sub_fields'   => [
                        ['key' => 'field_wc_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'],
                        ['key' => 'field_wc_meta', 'label' => 'Date / venue', 'name' => 'meta', 'type' => 'text'],
                        [
                            'key'           => 'field_wc_meta_position',
                            'label'         => 'Date / venue position',
                            'name'          => 'meta_position',
                            'type'          => 'select',
                            'choices'       => [
                                'before_price' => 'Before price',
                                'after_price'  => 'After price',
                            ],
                            'default_value' => 'before_price',
                        ],
                        ['key' => 'field_wc_price', 'label' => 'Price', 'name' => 'price', 'type' => 'text'],
                        ['key' => 'field_wc_price_note', 'label' => 'Price note', 'name' => 'price_note', 'type' => 'text'],
                        ['key' => 'field_wc_learn_heading', 'label' => 'Learn heading', 'name' => 'learn_heading', 'type' => 'text', 'default_value' => "What You'll Learn"],
                        [
                            'key'          => 'field_wc_learn_items',
                            'label'        => 'What you\'ll learn',
                            'name'         => 'learn_items',
                            'type'         => 'repeater',
                            'layout'       => 'table',
                            'button_label' => 'Add item',
                            'sub_fields'   => [
                                ['key' => 'field_wc_learn_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text'],
                            ],
                        ],
                        ['key' => 'field_wc_image', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'],
                        [
                            'key'           => 'field_wc_media_position',
                            'label'         => 'Image position',
                            'name'          => 'media_position',
                            'type'          => 'select',
                            'choices'       => ['right' => 'Right', 'left' => 'Left'],
                            'default_value' => 'right',
                        ],
                        ['key' => 'field_wc_btn_text', 'label' => 'Button text', 'name' => 'button_text', 'type' => 'text'],
                        ['key' => 'field_wc_btn_url', 'label' => 'Button URL', 'name' => 'button_url', 'type' => 'url'],
                    ],
                ],
            ],
        ],
        'layout_dual_feature' => [
            'key'        => 'layout_dual_feature',
            'name'       => 'dual_feature',
            'label'      => 'Dual Feature',
            'display'    => 'block',
            'sub_fields' => [
                ['key' => 'field_df_left_image', 'label' => 'Left Image', 'name' => 'left_image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'],
                ['key' => 'field_df_left_heading', 'label' => 'Left Heading', 'name' => 'left_heading', 'type' => 'text'],
                ['key' => 'field_df_left_list_two_columns', 'label' => 'Left list layout', 'name' => 'left_list_two_columns', 'type' => 'true_false', 'ui' => 1, 'ui_on_text' => '2 columns', 'ui_off_text' => '1 column', 'default_value' => 0],
                ['key' => 'field_df_left_list_items', 'label' => 'Left list items', 'name' => 'left_list_items', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Add item', 'sub_fields' => [['key' => 'field_df_left_list_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text']]],
                ['key' => 'field_df_right_image', 'label' => 'Right Image', 'name' => 'right_image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'],
                ['key' => 'field_df_right_heading', 'label' => 'Right Heading', 'name' => 'right_heading', 'type' => 'text'],
                ['key' => 'field_df_right_list_two_columns', 'label' => 'Right list layout', 'name' => 'right_list_two_columns', 'type' => 'true_false', 'ui' => 1, 'ui_on_text' => '2 columns', 'ui_off_text' => '1 column', 'default_value' => 0],
                ['key' => 'field_df_right_list_items', 'label' => 'Right list items', 'name' => 'right_list_items', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Add item', 'sub_fields' => [['key' => 'field_df_right_list_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text']]],
                ['key' => 'field_df_btn_text', 'label' => 'Button Text', 'name' => 'button_text', 'type' => 'text'],
                ['key' => 'field_df_btn_url', 'label' => 'Button URL', 'name' => 'button_url', 'type' => 'url'],
            ],
        ],
    ];
}

/**
 * Restore Page Builder ACF JSON from git when the on-disk copy is corrupted.
 *
 * @return bool
 */
function jhg_restore_page_builder_acf_json_from_git(): bool
{
    $path     = get_stylesheet_directory() . '/acf-json/group_jhg_page_modules.json';
    $relative = 'wp-content/themes/jhg-maven/acf-json/group_jhg_page_modules.json';
    $command  = sprintf(
        'git -C %s show HEAD:%s',
        escapeshellarg(untrailingslashit(ABSPATH)),
        escapeshellarg($relative)
    );

    $json = shell_exec($command);

    if (! is_string($json) || '' === trim($json)) {
        return false;
    }

    $data = json_decode($json, true);

    if (! is_array($data) || ! isset($data['fields'][0]['layouts'])) {
        return false;
    }

    return false !== file_put_contents($path, $json);
}

/**
 * Prevent ACF from overwriting acf-json during programmatic imports.
 *
 * @param mixed $path
 * @return mixed
 */
function jhg_acf_disable_json_save_during_import($path)
{
    return false;
}

/**
 * @return bool
 */
function jhg_patch_page_builder_acf_json(): bool
{
    $path = get_stylesheet_directory() . '/acf-json/group_jhg_page_modules.json';

    if (! is_readable($path)) {
        if (! jhg_restore_page_builder_acf_json_from_git()) {
            return false;
        }
    }

    $data = json_decode(file_get_contents($path), true);

    if (! is_array($data) || ! isset($data['fields'][0]['layouts'])) {
        if (! jhg_restore_page_builder_acf_json_from_git()) {
            return false;
        }

        $data = json_decode(file_get_contents($path), true);

        if (! is_array($data) || ! isset($data['fields'][0]['layouts'])) {
            return false;
        }
    }

    $layouts = &$data['fields'][0]['layouts'];

    if (isset($layouts['layout_text_media'])) {
        foreach ($layouts['layout_text_media']['sub_fields'] as $index => $sub_field) {
            if (($sub_field['name'] ?? '') === 'style' && isset($layouts['layout_text_media']['sub_fields'][ $index ]['choices'])) {
                $layouts['layout_text_media']['sub_fields'][ $index ]['choices']['gradient'] = 'Gradient panel';
                $layouts['layout_text_media']['sub_fields'][ $index ]['choices']['band']      = 'Courses band';
            }

            if (($sub_field['name'] ?? '') === 'checklist_icon_style' && isset($layouts['layout_text_media']['sub_fields'][ $index ]['choices'])) {
                $layouts['layout_text_media']['sub_fields'][ $index ]['choices']['plain'] = 'Plain text (no icons)';
            }
        }

        jhg_acf_add_sub_fields_list($layouts['layout_text_media']['sub_fields'], [
            ['key' => 'field_tmed_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text'],
            [
                'key'          => 'field_tm_checklist_note',
                'label'        => 'After Checklist Note',
                'name'         => 'checklist_note',
                'type'         => 'textarea',
                'rows'         => 2,
                'instructions' => 'Optional. Use “Label: description” — text before the colon is bold navy.',
            ],
            [
                'key'           => 'field_tm_checklist_style',
                'label'         => 'Checklist icons',
                'name'          => 'checklist_icon_style',
                'type'          => 'select',
                'choices'       => [
                    'navy'    => 'Navy circle (default)',
                    'feature' => 'Red check (membership style)',
                    'plain'   => 'Plain text (no icons)',
                ],
                'default_value' => 'navy',
            ],
            ['key' => 'field_tm_btn_text', 'label' => 'Button Text', 'name' => 'button_text', 'type' => 'text'],
            ['key' => 'field_tm_btn_url', 'label' => 'Button URL', 'name' => 'button_url', 'type' => 'url'],
            ['key' => 'field_tm_secondary_heading', 'label' => 'Second Checklist Heading', 'name' => 'secondary_checklist_heading', 'type' => 'text'],
            [
                'key'          => 'field_tm_secondary_checklist',
                'label'        => 'Second Checklist',
                'name'         => 'secondary_checklist',
                'type'         => 'repeater',
                'layout'       => 'table',
                'button_label' => 'Add Item',
                'sub_fields'   => [
                    ['key' => 'field_tm_secondary_check_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text'],
                ],
            ],
        ]);
    }

    if (isset($layouts['layout_cta_banner'])) {
        jhg_acf_add_sub_fields_list($layouts['layout_cta_banner']['sub_fields'], [
            [
                'key'          => 'field_cta_checklist',
                'label'        => 'Checklist',
                'name'         => 'checklist',
                'type'         => 'repeater',
                'layout'       => 'table',
                'button_label' => 'Add Item',
                'sub_fields'   => [
                    ['key' => 'field_cta_check_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text'],
                ],
            ],
        ]);
    }

    if (isset($layouts['layout_lead_text'])) {
        jhg_acf_add_sub_fields_list($layouts['layout_lead_text']['sub_fields'], [
            ['key' => 'field_lead_btn_text', 'label' => 'Button Text', 'name' => 'button_text', 'type' => 'text'],
            ['key' => 'field_lead_btn_url', 'label' => 'Button URL', 'name' => 'button_url', 'type' => 'url'],
            ['key' => 'field_lead_btn_2_text', 'label' => 'Second Button Text', 'name' => 'button_2_text', 'type' => 'text'],
            ['key' => 'field_lead_btn_2_url', 'label' => 'Second Button URL', 'name' => 'button_2_url', 'type' => 'url'],
        ]);
    }

    if (isset($layouts['layout_gradient_intro'])) {
        jhg_acf_add_sub_fields_list($layouts['layout_gradient_intro']['sub_fields'], [
            ['key' => 'field_gi_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text'],
        ]);
    }

    if (isset($layouts['layout_stats_band'])) {
        jhg_acf_add_sub_fields_list($layouts['layout_stats_band']['sub_fields'], [
            [
                'key'           => 'field_sb_band_style',
                'label'         => 'Layout',
                'name'          => 'band_style',
                'type'          => 'select',
                'choices'       => [
                    'feature' => 'Feature band (gradient)',
                    'metrics' => 'Metrics row (Services)',
                ],
                'default_value' => 'feature',
            ],
            ['key' => 'field_sb_section_heading', 'label' => 'Section Heading', 'name' => 'section_heading', 'type' => 'text'],
        ]);
    }

    if (isset($layouts['layout_icon_card_grid'])) {
        jhg_acf_add_sub_fields_list($layouts['layout_icon_card_grid']['sub_fields'], [
            [
                'key'           => 'field_icg_card_style',
                'label'         => 'Card layout',
                'name'          => 'card_style',
                'type'          => 'select',
                'choices'       => [
                    'simple'   => 'Simple (linked cards)',
                    'detailed' => 'Detailed (icon, title, description, button)',
                ],
                'default_value' => 'simple',
                'ui'            => 1,
            ],
        ]);

        foreach ($layouts['layout_icon_card_grid']['sub_fields'] as $index => $sub_field) {
            if (($sub_field['name'] ?? '') !== 'cards' || empty($sub_field['sub_fields'])) {
                continue;
            }

            jhg_acf_add_sub_fields_list($layouts['layout_icon_card_grid']['sub_fields'][ $index ]['sub_fields'], [
                ['key' => 'field_icg_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3, 'parent_repeater' => 'field_icg_cards'],
                ['key' => 'field_icg_btn_text', 'label' => 'Button Text', 'name' => 'button_text', 'type' => 'text', 'parent_repeater' => 'field_icg_cards'],
                ['key' => 'field_icg_btn_url', 'label' => 'Button URL', 'name' => 'button_url', 'type' => 'url', 'parent_repeater' => 'field_icg_cards'],
            ]);
        }
    }

    if (isset($layouts['layout_testimonials'])) {
        jhg_acf_add_sub_fields_list($layouts['layout_testimonials']['sub_fields'], [
            ['key' => 'field_t_intro', 'label' => 'Intro', 'name' => 'intro', 'type' => 'textarea', 'rows' => 3],
            [
                'key'           => 'field_t_show_three_desktop',
                'label'         => 'Desktop cards',
                'name'          => 'show_three_desktop',
                'type'          => 'true_false',
                'instructions'  => 'Off = 2 cards (default). On = 3 cards on screens 1200px and up.',
                'ui'            => 1,
                'ui_on_text'    => '3 cards',
                'ui_off_text'   => '2 cards',
                'default_value' => 0,
            ],
        ]);

        $testimonial_post_type = function_exists('jhg_testimonial_post_type')
            ? jhg_testimonial_post_type()
            : 'jhg_testimonial';

        jhg_acf_replace_sub_field_by_name($layouts['layout_testimonials']['sub_fields'], 'reviews', [
            'key'           => 'field_t_testimonials',
            'label'         => 'Testimonials',
            'name'          => 'testimonials',
            'type'          => 'relationship',
            'instructions'  => 'Select and reorder testimonials from the shared library.',
            'required'      => 0,
            'post_type'     => [$testimonial_post_type],
            'taxonomy'      => [],
            'filters'       => ['search'],
            'elements'      => ['featured_image'],
            'min'           => 1,
            'max'           => 0,
            'return_format' => 'object',
        ]);
    }

    $canonical_layouts  = jhg_page_builder_canonical_layouts();
    $replace_sub_fields = ['layout_gradient_checklist'];

    foreach ($canonical_layouts as $layout_key => $canonical) {
        if (! isset($layouts[ $layout_key ])) {
            $layouts[ $layout_key ] = $canonical;
            continue;
        }

        $layouts[ $layout_key ] = jhg_acf_merge_layout(
            $layouts[ $layout_key ],
            $canonical,
            in_array($layout_key, $replace_sub_fields, true)
        );
    }

    $has_service_location = false;

    foreach ($data['location'] ?? [] as $rule_group) {
        if (! is_array($rule_group)) {
            continue;
        }

        foreach ($rule_group as $rule) {
            if (($rule['param'] ?? '') === 'post_type' && ($rule['value'] ?? '') === 'jhg_service') {
                $has_service_location = true;
                break 2;
            }
        }
    }

    if (! $has_service_location) {
        $data['location'][] = [
            [
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'jhg_service',
            ],
        ];
    }

    $data['modified'] = time();

    return false !== file_put_contents($path, wp_json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n");
}

/**
 * @return array<int, int>
 */
function jhg_get_page_builder_field_group_post_ids(): array
{
    global $wpdb;

    $ids = $wpdb->get_col(
        "SELECT ID
         FROM {$wpdb->posts}
         WHERE post_type = 'acf-field-group'
         AND post_name = 'group_jhg_page_modules'
         ORDER BY ID ASC"
    );

    return array_map('intval', $ids ?: []);
}

/**
 * Keep one Page Builder field group post and remove accidental duplicates.
 *
 * @return int Canonical field group post ID.
 */
function jhg_cleanup_page_builder_field_group_duplicates(): int
{
    $ids = jhg_get_page_builder_field_group_post_ids();

    if (! $ids) {
        return 0;
    }

    $keep = array_shift($ids);

    if (function_exists('acf_delete_field_group')) {
        foreach ($ids as $duplicate_id) {
            acf_delete_field_group($duplicate_id);
        }
    }

    return $keep;
}

/**
 * Import the patched JSON field group into the WordPress database.
 *
 * @return bool
 */
function jhg_sync_page_builder_acf_db(): bool
{
    if (! function_exists('acf_import_field_group')) {
        return false;
    }

    if (! jhg_patch_page_builder_acf_json()) {
        return false;
    }

    $path = get_stylesheet_directory() . '/acf-json/group_jhg_page_modules.json';

    if (! is_readable($path)) {
        return false;
    }

    $field_group = json_decode(file_get_contents($path), true);

    if (! is_array($field_group)) {
        return false;
    }

    $canonical_id = jhg_cleanup_page_builder_field_group_duplicates();

    if ($canonical_id > 0) {
        $field_group['ID'] = $canonical_id;
    }

    add_filter('acf/settings/save_json', 'jhg_acf_disable_json_save_during_import', 999);

    acf_import_field_group($field_group);

    remove_filter('acf/settings/save_json', 'jhg_acf_disable_json_save_during_import', 999);

    if (function_exists('acf_get_store')) {
        $store = acf_get_store('field-groups');

        if ($store) {
            $store->reset();
        }
    }

    return true;
}
