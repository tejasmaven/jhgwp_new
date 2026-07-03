<?php

/**
 * Theme performance tweaks.
 */

if (! defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
});

add_action('wp_footer', function () {
    wp_deregister_script('wp-embed');
});

add_filter('wp_default_scripts', function ($scripts) {
    if (! is_admin() && isset($scripts->registered['jquery'])) {
        $jquery = $scripts->registered['jquery'];
        if ($jquery->deps) {
            $jquery->deps = array_diff($jquery->deps, ['jquery-migrate']);
        }
    }
});

add_action('wp_enqueue_scripts', function () {
    wp_scripts()->add_data('jquery', 'group', 1);
    wp_scripts()->add_data('jquery-core', 'group', 1);
}, 1);

add_action('wp_head', function () {
    $style = get_stylesheet_uri();
    echo '<link rel="preload" href="' . esc_url($style) . '" as="style" />';
}, 1);

add_action('wp_head', function () {
    ?>
    <style>
        @font-face {
            font-display: swap;
        }
    </style>
    <?php
});

add_action('wp_enqueue_scripts', function () {
    if (! is_user_logged_in()) {
        wp_deregister_style('dashicons');
    }
}, 100);

add_filter('the_content', function ($content) {
    return str_replace('<iframe', '<iframe loading="lazy"', $content);
});

add_action('init', function () {
    if (! is_admin()) {
        wp_deregister_script('heartbeat');
    }
});

add_filter('script_loader_src', 'jhg_remove_ver_except_theme', 10, 2);
add_filter('style_loader_src', 'jhg_remove_ver_except_theme', 10, 2);

function jhg_remove_ver_except_theme($src, $handle = '')
{
    if (is_string($handle) && str_starts_with($handle, 'jhg-')) {
        return $src;
    }

    if (strpos($src, '?ver=') !== false) {
        $src = remove_query_arg('ver', $src);
    }

    return $src;
}

add_filter('xmlrpc_enabled', '__return_false');

add_action('wp_head', 'preload_dynamic_lcp_image', 1);

function preload_dynamic_lcp_image()
{
    if (is_admin()) {
        return;
    }

    $image_url = '';

    if (is_singular()) {
        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }

    if (is_front_page() && ! $image_url) {
        $front_id = get_option('page_on_front');
        if ($front_id) {
            $image_url = get_the_post_thumbnail_url($front_id, 'full');
        }
    }

    if (! $image_url) {
        return;
    }

    echo '<link rel="preload" as="image" href="' . esc_url($image_url) . '" fetchpriority="high">';
}
