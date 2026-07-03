<?php

/**
 * Custom post types.
 */

if (! defined('ABSPATH')) {
    exit;
}

const JHG_TEAM_POST_TYPE     = 'jhg_team';


function jhg_register_team_post_type(): void
{
    register_post_type(JHG_TEAM_POST_TYPE, [
        'labels'              => [
            'name'               => __('Teams', 'jhg-maven'),
            'singular_name'      => __('Team', 'jhg-maven'),
            'add_new'            => __('Add Team', 'jhg-maven'),
            'add_new_item'       => __('Add New Team', 'jhg-maven'),
            'edit_item'          => __('Edit Team', 'jhg-maven'),
            'new_item'           => __('New Team', 'jhg-maven'),
            'view_item'          => __('View Team', 'jhg-maven'),
            'search_items'       => __('Search Teams', 'jhg-maven'),
            'not_found'          => __('No teams found.', 'jhg-maven'),
            'not_found_in_trash' => __('No teams found in Trash.', 'jhg-maven'),
            'all_items'          => __('All Teams', 'jhg-maven'),
            'menu_name'          => __('Teams', 'jhg-maven'),
        ],
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_icon'           => 'dashicons-businessperson',
        'menu_position'       => 26,
        'has_archive'         => false,
        'hierarchical'        => false,
        'supports'            => ['title', 'thumbnail', 'revisions'],
        'capability_type'     => 'page',
        'map_meta_cap'        => true,
        'rewrite'             => [
            'slug'       => 'teams',
            'with_front' => false,
        ],
        'query_var'           => true,
        'show_in_rest'        => false,
    ]);
}

add_action('init', 'jhg_register_team_post_type');

function jhg_maybe_flush_team_rewrite_rules(): void
{
    if (get_option('jhg_team_rewrite_flushed')) {
        return;
    }

    flush_rewrite_rules(false);
    update_option('jhg_team_rewrite_flushed', 1, true);
}

add_action('init', 'jhg_maybe_flush_team_rewrite_rules', 99);
