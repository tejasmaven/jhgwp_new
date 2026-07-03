<?php

if (! defined('ABSPATH')) {
    exit;
}

const JHG_TESTIMONIAL_POST_TYPE = 'jhg_testimonial';
function jhg_register_testimonial_post_type(): void
{
    register_post_type(JHG_TESTIMONIAL_POST_TYPE, [
        'labels'              => [
            'name'               => __('Testimonials', 'jhg-maven'),
            'singular_name'      => __('Testimonial', 'jhg-maven'),
            'add_new'            => __('Add Testimonial', 'jhg-maven'),
            'add_new_item'       => __('Add New Testimonial', 'jhg-maven'),
            'edit_item'          => __('Edit Testimonial', 'jhg-maven'),
            'new_item'           => __('New Testimonial', 'jhg-maven'),
            'view_item'          => __('View Testimonial', 'jhg-maven'),
            'search_items'       => __('Search Testimonials', 'jhg-maven'),
            'not_found'          => __('No testimonials found.', 'jhg-maven'),
            'not_found_in_trash' => __('No testimonials found in Trash.', 'jhg-maven'),
            'all_items'          => __('All Testimonials', 'jhg-maven'),
            'menu_name'          => __('Testimonials', 'jhg-maven'),
        ],
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => true,
        'menu_icon'           => 'dashicons-format-quote',
        'menu_position'       => 27,
        'has_archive'         => false,
        'hierarchical'        => false,
        'supports'            => ['title', 'thumbnail', 'revisions'],
        'capability_type'     => 'page',
        'map_meta_cap'        => true,
        'query_var'           => false,
        'show_in_rest'        => false,
    ]);
}

add_action('init', 'jhg_register_testimonial_post_type');
function jhg_testimonial_post_type(): string
{
    return defined('JHG_TESTIMONIAL_POST_TYPE') ? JHG_TESTIMONIAL_POST_TYPE : 'jhg_testimonial';
}

function jhg_testimonial_seed_library(): array
{
    return [
        'sam-m-franchise-owner' => [
            'title'       => 'Sam M, Franchise Owner',
            'quote'       => 'We\'ve never had to fear the CCMA again. JHG is always one step ahead.',
            'rating'      => 5,
            'avatar_file' => 'sam-m-franchise-owner.png',
        ],
        'hr-director-national-retail' => [
            'title'       => 'HR Director, National Retail Chain',
            'quote'       => 'They\'ve won every case we\'ve sent them. Every. Single. One.',
            'rating'      => 5,
            'avatar_file' => 'hr-director-national-retail.png',
        ],
        'lindi-m-operations-manager' => [
            'title'       => 'Lindi M, Operations Manager',
            'quote'       => 'They manage our payroll, HR, and compliance — it\'s like having a full team on tap.',
            'rating'      => 4,
            'avatar_file' => 'lindi-m-operations-manager.png',
        ],
        'nadia-p-hr-manager' => [
            'title'       => 'Nadia P, HR Manager',
            'quote'       => 'The workshop gave me practical tools I used the very next week. Clear, relevant, and backed by real legal insight.',
            'rating'      => 5,
            'avatar_file' => 'nadia-p-hr-manager.jpg',
        ],
        'thabo-k-operations-director' => [
            'title'       => 'Thabo K, Operations Director',
            'quote'       => 'Finally training that speaks to South African labour law — not generic theory. Our team left confident and prepared.',
            'rating'      => 4,
            'avatar_file' => 'thabo-k-operations-director.jpg',
        ],
        'lerato-m-business-owner' => [
            'title'       => 'Lerato M, Business Owner',
            'quote'       => 'Excellent facilitation and templates. The CPD certificate was a bonus for our compliance records.',
            'rating'      => 5,
            'avatar_file' => 'lerato-m-business-owner.jpg',
        ],
    ];
}

function jhg_testimonial_assets_dir(): string
{
    return trailingslashit(get_template_directory()) . 'assets/images/testimonials/';
}


function jhg_testimonial_bundled_avatar(string $slug, string $name = ''): ?array
{
    $library = jhg_testimonial_seed_library();

    if (! isset($library[$slug]['avatar_file'])) {
        return null;
    }

    $file = (string) $library[$slug]['avatar_file'];
    $path = jhg_testimonial_assets_dir() . $file;

    if (! is_readable($path)) {
        return null;
    }

    return [
        'url' => trailingslashit(get_template_directory_uri()) . 'assets/images/testimonials/' . rawurlencode($file),
        'alt' => $name ?: (string) ($library[$slug]['title'] ?? ''),
    ];
}

function jhg_format_testimonial_review($post): ?array
{
    $post = get_post($post);

    if (! $post instanceof WP_Post || jhg_testimonial_post_type() !== $post->post_type || 'publish' !== $post->post_status) {
        return null;
    }

    $quote  = function_exists('get_field') ? trim((string) get_field('quote', $post->ID)) : '';
    $rating = function_exists('get_field') ? (int) get_field('rating', $post->ID) : 0;
    $name   = trim(get_the_title($post));

    if ('' === $quote && '' === $name) {
        return null;
    }

    $avatar = null;

    if (has_post_thumbnail($post)) {
        $image_id  = (int) get_post_thumbnail_id($post);
        $image_src = wp_get_attachment_image_src($image_id, 'thumbnail');

        if ($image_src) {
            $avatar = [
                'ID'  => $image_id,
                'url' => $image_src[0],
                'alt' => trim((string) get_post_meta($image_id, '_wp_attachment_image_alt', true)) ?: $name,
            ];
        }
    }

    if (! $avatar) {
        $bundled = jhg_testimonial_bundled_avatar($post->post_name, $name);

        if ($bundled) {
            $avatar = [
                'ID'  => 0,
                'url' => $bundled['url'],
                'alt' => $bundled['alt'],
            ];
        }
    }

    return [
        'rating' => max(0, min(5, $rating)),
        'quote'  => $quote,
        'name'   => $name,
        'avatar' => $avatar,
    ];
}

function jhg_get_selected_testimonial_reviews($selection): array
{
    if (! $selection) {
        return [];
    }

    if (! is_array($selection)) {
        $selection = [$selection];
    }

    $reviews = [];

    foreach ($selection as $item) {
        $post_id = 0;

        if ($item instanceof WP_Post) {
            $post_id = (int) $item->ID;
        } elseif (is_numeric($item)) {
            $post_id = (int) $item;
        } elseif (is_array($item)) {
            if (! empty($item['ID'])) {
                $post_id = (int) $item['ID'];
            } elseif (! empty($item['id'])) {
                $post_id = (int) $item['id'];
            }
        }

        if (! $post_id) {
            continue;
        }

        $review = jhg_format_testimonial_review($post_id);

        if ($review) {
            $reviews[] = $review;
        }
    }

    return $reviews;
}
