<?php

/**
 * Custom post types.
 */

if (! defined('ABSPATH')) {
    exit;
}

const JHG_SERVICE_POST_TYPE     = 'jhg_service';


function jhg_register_service_post_type(): void
{
    register_post_type(JHG_SERVICE_POST_TYPE, [
        'labels'              => [
            'name'               => __('Services', 'jhg-maven'),
            'singular_name'      => __('Service', 'jhg-maven'),
            'add_new'            => __('Add Service', 'jhg-maven'),
            'add_new_item'       => __('Add New Service', 'jhg-maven'),
            'edit_item'          => __('Edit Service', 'jhg-maven'),
            'new_item'           => __('New Service', 'jhg-maven'),
            'view_item'          => __('View Service', 'jhg-maven'),
            'search_items'       => __('Search Services', 'jhg-maven'),
            'not_found'          => __('No services found.', 'jhg-maven'),
            'not_found_in_trash' => __('No services found in Trash.', 'jhg-maven'),
            'all_items'          => __('All Services', 'jhg-maven'),
            'menu_name'          => __('Services', 'jhg-maven'),
        ],
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_icon'           => 'dashicons-clipboard',
        'menu_position'       => 26,
        'has_archive'         => false,
        'hierarchical'        => false,
        'supports'            => ['title', 'thumbnail', 'revisions'],
        'capability_type'     => 'page',
        'map_meta_cap'        => true,
        'rewrite'             => [
            'slug'       => 'services',
            'with_front' => false,
        ],
        'query_var'           => true,
        'show_in_rest'        => false,
    ]);
}

add_action('init', 'jhg_register_service_post_type');

function jhg_maybe_flush_service_rewrite_rules(): void
{
    if (get_option('jhg_service_rewrite_flushed')) {
        return;
    }

    flush_rewrite_rules(false);
    update_option('jhg_service_rewrite_flushed', 1, true);
}

add_action('init', 'jhg_maybe_flush_service_rewrite_rules', 99);
