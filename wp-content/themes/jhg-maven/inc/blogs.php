<?php

if (! defined('ABSPATH')) {
    exit;
}

function track_post_views(int $post_id): void
{
    if (!is_single()) return;
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    $count = (int) get_post_meta($post_id, 'post_views_count', true);
    $count++;
    update_post_meta($post_id, 'post_views_count', $count);
}

function track_post_views_on_load()
{
    if (is_single()) {
        track_post_views(get_the_ID());
    }
}
add_action('wp_head', 'track_post_views_on_load');
