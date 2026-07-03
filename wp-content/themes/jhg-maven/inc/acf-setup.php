<?php

/**
 * ACF local JSON sync and Theme Settings options page.
 */

if (! defined('ABSPATH')) {
    exit;
}

add_filter('acf/settings/save_json', function ($path) {
    $dir = get_stylesheet_directory() . '/acf-json';
    if (! file_exists($dir)) {
        wp_mkdir_p($dir);
    }
    return $dir;
});

add_filter('acf/settings/load_json', function ($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
});

add_action('acf/init', function () {
    if (! function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page([
        'page_title' => __('Theme Settings', 'jhg-maven'),
        'menu_title' => __('Theme Settings', 'jhg-maven'),
        'menu_slug'  => 'jhg-theme-settings',
        'capability' => 'edit_theme_options',
        'redirect'   => false,
        'icon_url'   => 'dashicons-admin-customizer',
        'position'   => 59,
    ]);
});
