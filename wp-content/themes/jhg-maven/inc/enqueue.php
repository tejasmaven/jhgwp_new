<?php

/**
 * Front-end assets.
 */

if (! defined('ABSPATH')) {
    exit;
}

define('JHG_BOOTSTRAP_VERSION', '5.3.8');
define('JHG_FONTAWESOME_VERSION', '6.5.2');

function jhg_enqueue_assets()
{
    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@' . JHG_BOOTSTRAP_VERSION . '/dist/css/bootstrap.min.css',
        [],
        null
    );

    wp_enqueue_style(
        'bootstrap-icons',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
        [],
        '1.11.3'
    );

    wp_enqueue_style(
        'fontawesome',
        'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@' . JHG_FONTAWESOME_VERSION . '/css/all.min.css',
        [],
        JHG_FONTAWESOME_VERSION
    );

    wp_enqueue_style(
        'jhg-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
        [],
        null
    );

    $base_rel = '/assets/css/jhg-base.css';
    wp_enqueue_style(
        'jhg-base',
        get_theme_file_uri($base_rel),
        ['bootstrap'],
        null
        //jhg_asset_version($base_rel)
    );

    $header_nav_rel = '/assets/css/header-nav.css';
    //jhg_asset_version($main_rel),
    wp_enqueue_style(
        'jhg-header-nav',
        get_theme_file_uri($header_nav_rel),
        ['jhg-base'],
        null
    );

    $header_hero_banner_rel = '/assets/css/hero.css';
    wp_enqueue_style(
        'jhg-header-hero',
        get_theme_file_uri($header_hero_banner_rel),
        ['jhg-base'],
        null
    );

    $header_logo_strip_rel = '/assets/css/logo-strip.css';
    wp_enqueue_style(
        'jhg-header-logo-strip',
        get_theme_file_uri($header_logo_strip_rel),
        ['jhg-base'],
        null
    );

    wp_enqueue_script(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@' . JHG_BOOTSTRAP_VERSION . '/dist/js/bootstrap.bundle.min.js',
        [],
        JHG_BOOTSTRAP_VERSION,
        true
    );
    //jhg_asset_version($main_rel),
    $main_rel = '/assets/js/main.js';
    wp_enqueue_script(
        'jhg-main',
        get_theme_file_uri($main_rel),
        ['bootstrap'],
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'jhg_enqueue_assets');

// function jhg_asset_version($relative_path)
// {
//     $absolute = get_theme_file_path($relative_path);
//     return file_exists($absolute) ? filemtime($absolute) : wp_get_theme()->get('Version');
// }
