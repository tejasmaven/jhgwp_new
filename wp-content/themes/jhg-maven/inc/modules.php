<?php

if (! defined('ABSPATH')) {
    exit;
}

const JHG_MODULES_FIELD = 'page_modules';

function jhg_module_slug($layout)
{
    return str_replace('_', '-', sanitize_key($layout));
}

function jhg_render_modules($post_id = null)
{
    if (! function_exists('have_rows')) {
        return;
    }

    if (! have_rows(JHG_MODULES_FIELD, $post_id)) {
        return;
    }

    $index = 0;

    while (have_rows(JHG_MODULES_FIELD, $post_id)) {
        the_row();

        $layout = get_row_layout();
        $slug   = jhg_module_slug($layout);

        get_template_part('template-parts/modules/' . $slug, null, [
            'layout' => $layout,
            'slug'   => $slug,
            'index'  => $index,
        ]);

        $index++;
    }
}

function jhg_enqueue_module_styles()
{
    if (! function_exists('have_rows')) {
        return;
    }

    $post_id = get_queried_object_id();
    if (! $post_id || ! have_rows(JHG_MODULES_FIELD, $post_id)) {
        return;
    }

    $seen = [];

    while (have_rows(JHG_MODULES_FIELD, $post_id)) {
        the_row();
        $slug = jhg_module_slug(get_row_layout());

        if (isset($seen[$slug])) {
            continue;
        }
        $seen[$slug] = true;

        $relative = '/assets/css/modules/' . $slug . '.css';

        if (file_exists(get_theme_file_path($relative))) {
            wp_enqueue_style(
                'jhg-module-' . $slug,
                get_theme_file_uri($relative),
                ['jhg-base'],
                null
            );
        }

        if ('testimonials' === $slug) {
            jhg_enqueue_testimonials_swiper();
        }

        if ('trust-metrics' === $slug) {
            jhg_enqueue_trust_metrics_script();
        }

        if ('pricing-plans' === $slug) {
            jhg_enqueue_pricing_plans_script();
        }

        if ('contact-form' === $slug) {
            jhg_enqueue_contact_form_7_assets();
        }
    }

    if (function_exists('reset_rows')) {
        reset_rows();
    }
}

function jhg_enqueue_trust_metrics_script()
{
    static $done = false;

    if ($done) {
        return;
    }
    $done = true;

    $script_rel = '/assets/js/trust-metrics.js';

    wp_enqueue_script(
        'jhg-trust-metrics',
        get_theme_file_uri($script_rel),
        [],
        null,
        //jhg_asset_version($script_rel),
        true
    );
}

function jhg_enqueue_testimonials_swiper()
{
    static $done = false;

    if ($done) {
        return;
    }
    $done = true;

    $swiper_ver = '11.2.10';

    wp_enqueue_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        [],
        $swiper_ver
    );

    wp_enqueue_script(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [],
        $swiper_ver,
        true
    );

    $script_rel = '/assets/js/testimonials.js';
    wp_enqueue_script(
        'jhg-testimonials',
        get_theme_file_uri($script_rel),
        ['swiper'],
        null,
        //jhg_asset_version($script_rel),
        true
    );
}
function jhg_enqueue_pricing_plans_script()
{
    static $done = false;

    if ($done) {
        return;
    }
    $done = true;

    $script_rel = '/assets/js/pricing-plans.js';

    wp_enqueue_script(
        'jhg-pricing-plans',
        get_theme_file_uri($script_rel),
        [],
        null,
        //jhg_asset_version($script_rel),
        true
    );
}

add_action('wp_enqueue_scripts', 'jhg_enqueue_module_styles', 20);

function jhg_enqueue_contact_form_7_assets(): void
{
    static $done = false;

    if ($done || ! function_exists('wpcf7_enqueue_scripts')) {
        return;
    }

    $done = true;

    wpcf7_enqueue_scripts();
    wpcf7_enqueue_styles();

    wp_dequeue_style('contact-form-7');
    wp_dequeue_style('contact-form-7-rtl');
}
