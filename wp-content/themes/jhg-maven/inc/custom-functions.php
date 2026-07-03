<?php

function jhg_allow_svg_upload($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'jhg_allow_svg_upload');

function jhg_prefixed_menu_item_id($menu_id, $item, $args, $depth)
{
    if (! empty($args->jhg_menu_instance)) {
        return sanitize_html_class($args->jhg_menu_instance . '-' . $menu_id);
    }

    return $menu_id;
}
add_filter('nav_menu_item_id', 'jhg_prefixed_menu_item_id', 10, 4);

function jhg_render_primary_nav(string $menu_class, string $instance = 'desktop'): void
{
    if (! has_nav_menu('primary')) {
        return;
    }

    $walker = class_exists('Bootstrap_5_WP_Nav_Menu_Walker') ? new Bootstrap_5_WP_Nav_Menu_Walker() : '';

    wp_nav_menu([
        'theme_location'    => 'primary',
        'container'         => false,
        'menu_class'        => $menu_class,
        'fallback_cb'       => false,
        'depth'             => 2,
        'walker'            => $walker,
        'jhg_menu_instance' => $instance,
    ]);
}

function jhg_find_page_by_paths(array $paths): ?WP_Post
{
    foreach ($paths as $path) {
        $path = trim((string) $path, '/');
        if ('' === $path) {
            continue;
        }

        $page = get_page_by_path($path);
        if ($page instanceof WP_Post && 'publish' === $page->post_status) {
            return $page;
        }
    }

    return null;
}

function jhg_story_page_url(): string
{
    $page = jhg_find_page_by_paths(['story', 'jhg-story', 'the-jhg-story', 'about/story', 'about/jhg-story']);

    return $page ? get_permalink($page) : home_url('/story/');
}

function jhg_nav_menu_caret_svg(): string
{
    return '<span class="jhg-nav-caret" aria-hidden="true">'
        . '<svg width="7" height="10" viewBox="0 0 7 10" fill="none" xmlns="http://www.w3.org/2000/svg">'
        . '<path d="M0.750001 8.75L5.75 4.75L0.75 0.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>'
        . '</svg></span>';
}

function jhg_nav_menu_item_field(string $name, int $menu_item_id, $default = '')
{
    if ($menu_item_id < 1 || ! function_exists('get_field')) {
        return $default;
    }

    $value = get_field($name, $menu_item_id);

    if (null === $value || false === $value || '' === $value) {
        return $default;
    }

    return $value;
}

function jhg_nav_dropdown_parent_layout($item): string
{
    $item_id = is_object($item) ? (int) ($item->ID ?? 0) : 0;
    $label   = is_object($item) ? (string) ($item->title ?? '') : (string) $item;
    $layout  = $item_id > 0 ? (string) jhg_nav_menu_item_field('nav_dropdown_layout', $item_id, 'auto') : 'auto';

    if ('mega' === $layout || 'card' === $layout) {
        return $layout;
    }

    return 'Services' === $label ? 'mega' : 'card';
}

function jhg_nav_apply_dropdown_meta(int $menu_item_id, array $meta): void
{
    if ($menu_item_id < 1 || ! function_exists('update_field')) {
        return;
    }

    if (! empty($meta['layout'])) {
        update_field('nav_dropdown_layout', $meta['layout'], $menu_item_id);
    }

    if (! empty($meta['title'])) {
        update_field('nav_dropdown_title', $meta['title'], $menu_item_id);
    }

    if (! empty($meta['description'])) {
        update_field('nav_dropdown_description', $meta['description'], $menu_item_id);
    }

    if (! empty($meta['icon'])) {
        update_field('nav_dropdown_icon_preset', $meta['icon'], $menu_item_id);
    }
}

function jhg_nav_dropdown_resolve_meta($item, string $fallback_title = ''): array
{
    $item_id    = (int) ($item->ID ?? 0);
    $acf_title  = trim((string) jhg_nav_menu_item_field('nav_dropdown_title', $item_id, ''));
    $acf_desc   = trim((string) jhg_nav_menu_item_field('nav_dropdown_description', $item_id, ''));
    $acf_image  = jhg_nav_menu_item_field('nav_dropdown_icon', $item_id, null);
    $acf_preset = trim((string) jhg_nav_menu_item_field('nav_dropdown_icon_preset', $item_id, ''));

    return [
        'icon'        => $acf_preset !== '' ? $acf_preset : 'default',
        'icon_image'  => is_array($acf_image) ? $acf_image : null,
        'title'       => $acf_title !== '' ? $acf_title : $fallback_title,
        'description' => $acf_desc,
    ];
}

function jhg_nav_dropdown_render_icon(array $meta): string
{
    if (! empty($meta['icon_image']['url'])) {
        return '<img class="jhg-nav-dropdown-icon-img" src="' . esc_url($meta['icon_image']['url']) . '" alt="" aria-hidden="true" loading="lazy" decoding="async" />';
    }

    return jhg_nav_dropdown_icon_svg((string) ($meta['icon'] ?? 'default'));
}

function jhg_nav_dropdown_icon_svg(string $icon): string
{
    $stroke = 'currentColor';
    $sw     = '1.75';

    $aliases = [
        'rocket' => 'compliance',
        'book'   => 'blog',
    ];

    $icon = $aliases[$icon] ?? $icon;

    $icons = [
        'folder' => '<path d="M3 7.5V19a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1h-5.5L13 5H4a1 1 0 0 0-1 1.5Z" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linejoin="round"/>',
        'compliance' => '<path d="M9 3h6a1 1 0 0 1 1 1v2h2a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h2V4a1 1 0 0 1 1-1Z" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linejoin="round"/><path d="M9 12l2 2 4-5" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linecap="round" stroke-linejoin="round"/>',
        'membership' => '<rect x="5" y="3" width="14" height="18" rx="2" stroke="' . $stroke . '" stroke-width="' . $sw . '"/><path d="M9 8h6M9 12h6M9 16h4" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linecap="round"/>',
        'payroll' => '<circle cx="12" cy="8" r="3" stroke="' . $stroke . '" stroke-width="' . $sw . '"/><path d="M6 20v-1.5a6 6 0 0 1 12 0V20" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linecap="round"/><path d="M16 10h4M18 8v4" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linecap="round"/>',
        'wellbeing' => '<path d="M12 20s-6.5-4-6.5-9.5a4 4 0 0 1 7-2.5 4 4 0 0 1 7 2.5C19.5 16 12 20 12 20Z" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linejoin="round"/>',
        'team' => '<circle cx="9" cy="8" r="2.75" stroke="' . $stroke . '" stroke-width="' . $sw . '"/><path d="M4 19v-1.25a5 5 0 0 1 5-5h0a5 5 0 0 1 5 5V19" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linecap="round"/><circle cx="17" cy="9" r="2.25" stroke="' . $stroke . '" stroke-width="' . $sw . '"/><path d="M14.5 19v-.75a3.75 3.75 0 0 1 3-3.68" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linecap="round"/>',
        'story' => '<path d="M5 4h14v16H5V4Z" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linejoin="round"/><path d="M9 8h6M9 12h6M9 16h4" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linecap="round"/>',
        'blog' => '<path d="M6 4h9l5 5v11a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Z" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linejoin="round"/><path d="M15 4v5h5M8 12h8M8 16h5" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linecap="round" stroke-linejoin="round"/>',
        'default' => '<path d="M5 4h14v16H5V4Z" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linejoin="round"/><path d="M9 8h6M9 12h6M9 16h4" stroke="' . $stroke . '" stroke-width="' . $sw . '" stroke-linecap="round"/>',
    ];

    $paths = $icons[$icon] ?? $icons['default'];

    return '<svg class="jhg-nav-dropdown-icon-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">'
        . $paths
        . '</svg>';
}

function jhg_nav_dropdown_render_link(string $attributes, $item, string $fallback_title, string $layout = 'card'): string
{
    $meta  = jhg_nav_dropdown_resolve_meta($item, $fallback_title);
    $title = '' !== $meta['title'] ? $meta['title'] : $fallback_title;

    $html  = '<a' . $attributes . '>';
    $html .= '<span class="jhg-nav-dropdown-icon" aria-hidden="true">' . jhg_nav_dropdown_render_icon($meta) . '</span>';
    $html .= '<span class="jhg-nav-dropdown-copy">';
    $html .= '<span class="jhg-nav-dropdown-title title-sm">' . esc_html($title) . '</span>';

    if ('' !== $meta['description']) {
        $html .= '<span class="jhg-nav-dropdown-desc body-xs">' . esc_html($meta['description']) . '</span>';
    }

    $html .= '</span></a>';

    return $html;
}

class Bootstrap_5_WP_Nav_Menu_Walker extends Walker_Nav_Menu
{
    private string $dropdown_layout = 'card';

    function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);

        if (0 === $depth && 'mega' === $this->dropdown_layout) {
            $output .= "\n$indent<ul class=\"jhg-nav-dropdown jhg-nav-dropdown-mega\" role=\"menu\">\n";
            $output .= "$indent\t<li class=\"jhg-nav-dropdown-mega-panel\" role=\"presentation\">\n";
            $output .= "$indent\t\t<div class=\"container jhg-nav-dropdown-mega-inner\">\n";
            $output .= "$indent\t\t\t<ul class=\"jhg-nav-dropdown-mega-grid\" role=\"menu\">\n";

            return;
        }

        if (0 === $depth) {
            $output .= "\n$indent<ul class=\"jhg-nav-dropdown jhg-nav-dropdown-card\" role=\"menu\">\n";
            $output .= "$indent\t<li class=\"jhg-nav-dropdown-card-panel\" role=\"presentation\">\n";
            $output .= "$indent\t\t<div class=\"container jhg-nav-dropdown-card-inner\">\n";
            $output .= "$indent\t\t\t<ul class=\"jhg-nav-dropdown-card-list\" role=\"menu\">\n";

            return;
        }

        $output .= "\n$indent<ul class=\"jhg-nav-dropdown\" role=\"menu\">\n";
    }

    function end_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);

        if (0 === $depth && 'mega' === $this->dropdown_layout) {
            $output .= "$indent\t\t\t</ul>\n";
            $output .= "$indent\t\t</div>\n";
            $output .= "$indent\t</li>\n";
            $output .= "$indent</ul>\n";

            return;
        }

        if (0 === $depth) {
            $output .= "$indent\t\t\t</ul>\n";
            $output .= "$indent\t\t</div>\n";
            $output .= "$indent\t</li>\n";
            $output .= "$indent</ul>\n";

            return;
        }

        $output .= "$indent</ul>\n";
    }
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes      = empty($item->classes) ? array() : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes, true);
        $is_mobile    = ! empty($args->jhg_menu_instance) && 'mobile' === $args->jhg_menu_instance;

        if (0 === $depth) {
            $this->dropdown_layout = 'card';
            $classes[]             = 'nav-item';

            if ($has_children) {
                $classes[] = 'dropdown';
                $classes[] = 'jhg-nav-item-has-children';

                if (! $is_mobile) {
                    $classes[] = 'jhg-dropdown-hover';
                    $this->dropdown_layout = jhg_nav_dropdown_parent_layout($item);

                    if ('mega' === $this->dropdown_layout) {
                        $classes[] = 'jhg-nav-dropdown-mega-parent';
                    } else {
                        $classes[] = 'jhg-nav-dropdown-card-parent';
                    }
                }
            }
        } else {
            $classes = array_values(array_filter($classes, static function (string $class): bool {
                return ! in_array($class, [
                    'nav-item',
                    'dropdown',
                    'menu-item-has-children',
                    'jhg-dropdown-hover',
                    'jhg-nav-item-has-children',
                ], true);
            }));
            $classes[] = 'jhg-nav-dropdown-item';
        }

        $class_names = join(' ', array_filter($classes));
        $class_names = ' class="' . esc_attr($class_names) . '"';

        $atts = array();

        $is_active =
            !empty($item->current) ||
            !empty($item->current_item_ancestor) ||
            !empty($item->current_item_parent) ||
            in_array('current-menu-item', $item->classes) ||
            in_array('current_page_item', $item->classes);

        if ($depth === 0) {
            $atts['class'] = 'nav-link';

            if ($has_children && ! $is_mobile) {
                $atts['aria-haspopup'] = 'true';
                $atts['aria-expanded'] = 'false';
            }
        } else {
            $atts['class'] = 'jhg-nav-dropdown-link';
            $atts['role']  = 'menuitem';
        }
        if ($is_active) {
            $atts['class']  .= ' active';
        }

        $atts['href'] = ! empty($item->url) ? $item->url : '#';
        $attributes = '';

        foreach ($atts as $attr => $value) {
            $attributes .= ' ' . $attr . '="' . esc_attr($value) . '"';
        }

        $title = apply_filters('the_title', $item->title, $item->ID);

        $caret      = ($depth === 0 && $has_children) ? jhg_nav_menu_caret_svg() : '';
        $label_only = $has_children && in_array('jhg-nav-parent-label', $classes, true);

        $output .= $indent . '<li' . $class_names . '>';

        if (0 === $depth && $is_mobile && $has_children) {
            $label_class = 'nav-link jhg-mobile-label';

            if ($is_active) {
                $label_class .= ' active';
            }

            $output .= '<div class="jhg-mobile-item-row">';

            if ($label_only) {
                $output .= '<span class="' . esc_attr($label_class) . '">' . $title . '</span>';
            } else {
                $output .= '<a' . $attributes . '>' . $title . '</a>';
            }

            $output .= '<button type="button" class="jhg-mobile-expand" aria-expanded="false" aria-haspopup="true" aria-label="'
                . esc_attr(sprintf(__('Show %s submenu', 'jhg-maven'), $title))
                . '">' . $caret . '</button>';
            $output .= '</div>';
        } elseif ($label_only && 0 === $depth) {
            unset($atts['href']);
            $btn_atts = $atts;
            $btn_atts['class'] = trim(($btn_atts['class'] ?? 'nav-link') . ' jhg-nav-parent-trigger');
            $btn_attributes = '';

            foreach ($btn_atts as $attr => $value) {
                $btn_attributes .= ' ' . $attr . '="' . esc_attr($value) . '"';
            }

            $output .= '<button type="button"' . $btn_attributes . '>' . $title . $caret . '</button>';
        } elseif ($depth > 0) {
            $output .= jhg_nav_dropdown_render_link($attributes, $item, $title, $this->dropdown_layout);
        } else {
            $link_markup = $title;

            if ($depth === 0 && $has_children && ! $is_mobile) {
                $link_markup .= $caret;
            }

            $output .= '<a' . $attributes . '>' . $link_markup . '</a>';
        }
    }

    function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= "</li>\n";
    }
}

add_filter('body_class', function ($classes) {
    if (!is_front_page() && !is_home()) {
        $classes[] = 'inner-page';
    }
    return $classes;
});

function jhg_theme_option(string $name, $default = '')
{
    if (! function_exists('get_field')) {
        return $default;
    }

    $value = get_field($name, 'option');

    return ($value !== null && $value !== '') ? $value : $default;
}

function jhg_acf_link_url(mixed $value, string $fallback = ''): string
{
    if (is_array($value)) {
        $url = trim((string) ($value['url'] ?? ''));

        return '' !== $url ? $url : $fallback;
    }

    $url = trim((string) $value);

    return '' !== $url ? $url : $fallback;
}

function jhg_acf_link_target(mixed $value): string
{
    if (! is_array($value)) {
        return '';
    }

    $target = trim((string) ($value['target'] ?? ''));

    return in_array($target, ['_blank', '_self', '_parent', '_top'], true) ? $target : '';
}

function jhg_acf_link_attrs(mixed $value, string $target_override = ''): string
{
    $target = '' !== $target_override ? $target_override : jhg_acf_link_target($value);

    if ('' === $target) {
        return '';
    }

    $attrs = ' target="' . esc_attr($target) . '"';

    if ('_blank' === $target) {
        $attrs .= ' rel="noopener noreferrer"';
    }

    return $attrs;
}

function jhg_acf_link_value(string $url, string $title = '', string $target = ''): array
{
    return [
        'url'    => $url,
        'title'  => $title,
        'target' => $target,
    ];
}

// function jhg_site_content_defaults(): array
// {
//     return [
//         'footer_about'            => '',
//         'footer_address'          => '21 Lincoln Street, Bellville, 7530, Cape Town, Western Cape',
//         'footer_phone'            => '021 949 0445',
//         'footer_email'            => 'admin@jhg.co.za',
//         'contact_map_embed_url'   => 'https://maps.google.com/maps?q=21+Lincoln+Street,Bellville,7530,Cape+Town,Western+Cape&z=15&output=embed',
//         'contact_whatsapp_url'    => 'https://wa.me/27219490445',
//         'contact_head_office'     => "JHG – Labour Personnel Practitioners\n21 Lincoln Street, Bellville, 7530\nCape Town, Western Cape\n(Secure parking available onsite)",
//         'contact_office_hours'    => "Monday–Thursday: 8:30 AM – 4:30 PM\nFriday: 8:30 AM – 3:00 PM",
//         'contact_form_recipient'  => 'admin@jhg.co.za',
//         'newsletter_url'          => '',
//         'blog_intro'              => 'Cut through the confusion with practical, expert-written blogs on HR, CCMA, compliance, and legal risk based on 30+ years of real-world experience.',
//         'footer_terms_label'      => 'Terms and Conditions',
//         'footer_privacy_label'    => 'Privacy Policy',
//     ];
// }



// function jhg_contact_cf7_recipient(): string
// {
//     $recipient = trim((string) jhg_theme_option('contact_form_recipient', ''));

//     if ('' !== $recipient) {
//         return $recipient;
//     }

//     $email = trim((string) jhg_theme_option('footer_email', ''));

//     return '' !== $email ? $email : (jhg_site_content_defaults()['contact_form_recipient'] ?? '');
// }

function jhg_resolve_contact_details_fields(): array
{
    $from_page = static function (string $subfield, bool $as_link = false): string {
        if (! function_exists('get_sub_field')) {
            return '';
        }

        $value = get_sub_field($subfield);

        return $as_link ? jhg_acf_link_url($value) : trim((string) $value);
    };

    $from_option = static function (string $option_key, bool $as_link = false): string {
        $value = jhg_theme_option($option_key, '');

        return $as_link ? jhg_acf_link_url($value) : trim((string) $value);
    };

    $map_embed = $from_page('map_embed_url');

    if ('' === $map_embed) {
        $map_embed = $from_option('contact_map_embed_url');
    }

    $phone = $from_page('phone');

    if ('' === $phone) {
        $phone = $from_option('footer_phone');
    }

    $email = $from_page('email');

    if ('' === $email) {
        $email = $from_option('footer_email');
    }

    $whatsapp_url = $from_page('whatsapp_url', true);

    if ('' === $whatsapp_url) {
        $whatsapp_url = $from_option('contact_whatsapp_url', true);
    }

    $head_office = $from_page('head_office');

    if ('' === $head_office) {
        $head_office = $from_option('contact_head_office');

        if ('' === $head_office) {
            $head_office = $from_option('footer_address');
        }
    }

    $office_hours = $from_page('office_hours');

    if ('' === $office_hours) {
        $office_hours = $from_option('contact_office_hours');
    }

    return compact('map_embed', 'phone', 'email', 'whatsapp_url', 'head_office', 'office_hours');
}

function jhg_newsletter_url(): string
{
    $url = jhg_acf_link_url(jhg_theme_option('newsletter_url', ''));

    return '' !== $url ? $url : home_url('/contact/');
}

function jhg_blog_landing_intro_html(): string
{
    $intro = trim((string) jhg_theme_option('blog_intro', ''));

    if ('' !== $intro) {
        return '<p>' . esc_html($intro) . '</p>';
    }

    $blog_id = jhg_ensure_blog_page_id();

    if ($blog_id) {
        $content = jhg_copy_flexible_subfield($blog_id, 'lead_text', 'content');

        if (is_string($content) && '' !== trim($content)) {
            return $content;
        }
    }

    return '';
}

function jhg_blog_post_author_name(int $post_id = 0): string
{
    $post_id = $post_id > 0 ? $post_id : (int) get_the_ID();
    $author_id = (int) get_post_field('post_author', $post_id);

    if ($author_id < 1) {
        return '';
    }

    return trim((string) get_the_author_meta('display_name', $author_id));
}





function jhg_footer_link_is_legal(string $label, string $url = ''): bool
{
    $haystack = strtolower($label . ' ' . $url);

    foreach (['terms', 'privacy', 'conditions'] as $needle) {
        if (str_contains($haystack, $needle)) {
            return true;
        }
    }

    return false;
}

function jhg_footer_link_is_current(string $url): bool
{
    if ($url === '' || $url === '#') {
        return false;
    }

    $link_url = untrailingslashit(strtok($url, '#?'));

    if (is_front_page()) {
        $candidates = [untrailingslashit(home_url('/'))];

        $front_id = (int) get_option('page_on_front');
        if ($front_id) {
            $candidates[] = untrailingslashit(get_permalink($front_id));
        }

        return in_array($link_url, $candidates, true);
    }

    if (is_singular()) {
        return $link_url === untrailingslashit(get_permalink());
    }

    $current_url = home_url(add_query_arg([]));
    $link_path   = wp_parse_url($url, PHP_URL_PATH);
    $current_path = wp_parse_url($current_url, PHP_URL_PATH);

    if ($link_path && $current_path) {
        return untrailingslashit($link_path) === untrailingslashit($current_path);
    }

    return untrailingslashit($url) === untrailingslashit($current_url);
}

function jhg_footer_menu_item_is_current($item): bool
{
    if (! empty($item->current)) {
        return true;
    }

    $classes = is_array($item->classes ?? null) ? $item->classes : [];

    foreach (['current-menu-item', 'current_page_item'] as $class) {
        if (in_array($class, $classes, true)) {
            return true;
        }
    }

    return jhg_footer_link_is_current((string) ($item->url ?? ''));
}

function jhg_get_footer_company_links(): array
{
    $links = [];

    if (has_nav_menu('footer')) {
        $locations = get_nav_menu_locations();

        if (! empty($locations['footer'])) {
            $items = wp_get_nav_menu_items((int) $locations['footer']);

            if ($items) {
                foreach ($items as $item) {
                    if (jhg_footer_link_is_legal($item->title, $item->url)) {
                        continue;
                    }

                    $links[] = [
                        'label'   => $item->title,
                        'url'     => $item->url,
                        'current' => jhg_footer_menu_item_is_current($item),
                    ];
                }
            }
        }
    }

    return $links;
}
function jhg_footer_bottom_links(): array
{
    $links = [];

    if (has_nav_menu('footer-bottom-menu')) {
        $locations = get_nav_menu_locations();

        if (! empty($locations['footer-bottom-menu'])) {
            $items = wp_get_nav_menu_items((int) $locations['footer-bottom-menu']);

            if ($items) {
                foreach ($items as $item) {

                    $links[] = [
                        'label'   => $item->title,
                        'url'     => $item->url,
                        //'current' => jhg_footer_menu_item_is_current($item),
                    ];
                }
            }
        }
    }

    return $links;
}

function jhg_checklist_item_html(string $item): string
{
    $item = trim($item);

    if ('' === $item) {
        return '';
    }

    if (preg_match('/^(.+?)\s*(?:—|–|-)\s*(.+)$/u', $item, $matches)) {
        $title = wp_kses_post(trim($matches[1]));
        $desc  = esc_html(trim($matches[2]));

        return sprintf(
            '<span class="jhg-checklist-title">%1$s</span><span class="jhg-checklist-sep" aria-hidden="true"> - </span><span class="jhg-checklist-desc">%2$s</span>',
            $title,
            $desc
        );
    }

    return esc_html($item);
}

function jhg_checklist_note_html(string $note): string
{
    $note = trim($note);

    if ('' === $note) {
        return '';
    }

    if (preg_match('/^(.+?)\s*:\s*(.+)$/u', $note, $matches)) {
        $label = esc_html(trim($matches[1]));
        $text  = esc_html(trim($matches[2]));

        return sprintf(
            '<p class="jhg-checklist-note"><strong class="jhg-checklist-note-label">%1$s:</strong> %2$s</p>',
            $label,
            $text
        );
    }

    return sprintf('<p class="jhg-checklist-note">%s</p>', esc_html($note));
}

function jhg_render_button(string $text, array $args = []): void
{
    if ('' === trim($text)) {
        return;
    }

    $args['text'] = $text;

    get_template_part('template-parts/components/button', null, $args);
}

function jhg_pricing_price_parts(string $price): ?array
{
    $price = trim($price);

    if ('' === $price || ! str_contains($price, '/')) {
        return null;
    }

    $slash = strpos($price, '/');

    if (false === $slash) {
        return null;
    }

    return [
        'amount' => substr($price, 0, $slash + 1),
        'suffix' => substr($price, $slash + 1),
    ];
}

function jhg_acf_image_id($value): int
{
    if (is_array($value) && ! empty($value['ID'])) {
        return (int) $value['ID'];
    }

    return is_numeric($value) ? (int) $value : 0;
}

function jhg_kses_line_breaks(string $text): string
{
    if ('' === trim($text)) {
        return '';
    }

    $text = wp_kses($text, [
        'br' => [],
    ]);

    return nl2br($text, false);
}

function jhg_social_icon_classes(string $icon): string
{
    $icon = trim($icon);

    if ($icon === '') {
        return 'fa-solid fa-link';
    }

    if (str_contains($icon, 'fa-brands') || str_contains($icon, 'fa-solid') || str_contains($icon, 'fa-regular')) {
        return $icon;
    }

    $legacy = [
        'bi-facebook'  => 'fa-brands fa-facebook-f',
        'bi-linkedin'  => 'fa-brands fa-linkedin-in',
        'bi-instagram' => 'fa-brands fa-instagram',
        'bi-twitter'   => 'fa-brands fa-x-twitter',
        'bi-twitter-x' => 'fa-brands fa-x-twitter',
        'bi-youtube'   => 'fa-brands fa-youtube',
        'bi-tiktok'    => 'fa-brands fa-tiktok',
    ];

    if (isset($legacy[$icon])) {
        return $legacy[$icon];
    }

    if (str_starts_with($icon, 'fa-')) {
        $brand_icons = [
            'fa-facebook',
            'fa-facebook-f',
            'fa-linkedin',
            'fa-linkedin-in',
            'fa-instagram',
            'fa-x-twitter',
            'fa-twitter',
            'fa-youtube',
            'fa-tiktok',
            'fa-whatsapp',
        ];

        foreach ($brand_icons as $brand) {
            if ($icon === $brand) {
                return 'fa-brands ' . $icon;
            }
        }

        return 'fa-solid ' . $icon;
    }

    return 'fa-solid fa-' . ltrim($icon, '-');
}

function jhg_social_icon_label(string $icon): string
{
    $haystack = strtolower(trim($icon));

    foreach (
        [
            'facebook' => __('Facebook', 'jhg-maven'),
            'linkedin' => __('LinkedIn', 'jhg-maven'),
            'instagram' => __('Instagram', 'jhg-maven'),
            'twitter' => __('X (Twitter)', 'jhg-maven'),
            'x-twitter' => __('X (Twitter)', 'jhg-maven'),
            'youtube' => __('YouTube', 'jhg-maven'),
            'tiktok' => __('TikTok', 'jhg-maven'),
        ] as $needle => $label
    ) {
        if (str_contains($haystack, $needle)) {
            return $label;
        }
    }

    return __('Social link', 'jhg-maven');
}

function jhg_theme_linkedin_url(): string
{
    $social = function_exists('get_field') ? (get_field('social_links', 'option') ?: []) : [];

    if (is_array($social)) {
        foreach ($social as $row) {
            $icon = strtolower((string) ($row['icon'] ?? ''));

            if (jhg_acf_link_url($row['url'] ?? '') && str_contains($icon, 'linkedin')) {
                return jhg_acf_link_url($row['url']);
            }
        }
    }

    return 'https://www.linkedin.com/';
}

function jhg_copy_flexible_subfield(int $post_id, string $layout, string $subfield)
{
    $rows = get_field('page_modules', $post_id);

    if (! is_array($rows)) {
        return null;
    }

    foreach ($rows as $row) {
        if (($row['acf_fc_layout'] ?? '') === $layout && isset($row[$subfield])) {
            return $row[$subfield];
        }
    }

    return null;
}





































function jhg_text_grid_joint_positions(): array
{
    $joints = [];

    foreach (['0', '33.333%', '66.666%', '100%'] as $left) {
        foreach (['0', '50%', '100%'] as $top) {
            if ('0' === $left && '0' === $top) {
                continue;
            }

            $joints[] = [
                'top'  => $top,
                'left' => $left,
            ];
        }
    }

    return $joints;
}



function jhg_dual_feature_normalize_list_items($rows): array
{
    if (! is_array($rows)) {
        return [];
    }

    $items = [];

    foreach ($rows as $row) {
        if (! is_array($row)) {
            continue;
        }

        $text = trim((string) ($row['item'] ?? ''));

        if ('' !== $text) {
            $items[] = $text;
        }
    }

    return $items;
}

function jhg_dual_feature_render_list(array $items, bool $two_columns = false): void
{
    if (empty($items)) {
        return;
    }

    if (! $two_columns || count($items) < 2) {
        echo '<div class="jhg-dual-feature-list-wrap">';
        echo '<ul class="jhg-dual-feature-list">';
        foreach ($items as $item) {
            echo '<li>' . esc_html($item) . '</li>';
        }
        echo '</ul></div>';
        return;
    }

    $split_at = (int) ceil(count($items) / 2);
    $columns  = [
        array_slice($items, 0, $split_at),
        array_slice($items, $split_at),
    ];

    echo '<div class="jhg-dual-feature-lists">';
    foreach ($columns as $column_items) {
        if (empty($column_items)) {
            continue;
        }
        echo '<ul class="jhg-dual-feature-list">';
        foreach ($column_items as $item) {
            echo '<li>' . esc_html($item) . '</li>';
        }
        echo '</ul>';
    }
    echo '</div>';
}

function jhg_comparison_table_value_cell(string $value, string $column): void
{
    $value = trim($value);

    if ('' === $value) {
        return;
    }

    $is_jhg = ('jhg' === $column);
?>
    <span class="jhg-comparison-table-cell">
        <span class="jhg-comparison-table-icon <?php echo $is_jhg ? 'is-jhg' : 'is-generic'; ?>" aria-hidden="true">
            <i class="fa-solid <?php echo $is_jhg ? 'fa-check' : 'fa-xmark'; ?>"></i>
        </span>
        <span class="jhg-comparison-table-value body-md"><?php echo esc_html($value); ?></span>
    </span>
    <?php
}

function jhg_access_table_icon_cell(string $value): void
{
    $value = trim($value);

    if ('' === $value) {
        return;
    }

    $lower = strtolower($value);

    if (in_array($lower, ['yes', 'y'], true)) {
    ?>
        <span class="jhg-access-table-icon is-yes" aria-hidden="true">
            <i class="fa-solid fa-check"></i>
        </span>
        <span class="visually-hidden"><?php esc_html_e('Yes', 'jhg-maven'); ?></span>
    <?php
        return;
    }

    if (in_array($lower, ['no', 'n'], true)) {
    ?>
        <span class="jhg-access-table-icon is-no" aria-hidden="true">
            <i class="fa-solid fa-xmark"></i>
        </span>
        <span class="visually-hidden"><?php esc_html_e('No', 'jhg-maven'); ?></span>
<?php
        return;
    }

    echo '<span class="body-lg">' . esc_html($value) . '</span>';
}





























function jhg_get_service_by_slug(string $slug): ?WP_Post
{
    $slug = sanitize_title($slug);

    if ('' === $slug) {
        return null;
    }

    $posts = get_posts([
        'post_type'              => defined('JHG_SERVICE_POST_TYPE') ? JHG_SERVICE_POST_TYPE : 'jhg_service',
        'name'                   => $slug,
        'posts_per_page'         => 1,
        'post_status'            => 'publish',
        'suppress_filters'       => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    return ! empty($posts[0]) && $posts[0] instanceof WP_Post ? $posts[0] : null;
}

function jhg_service_url(string $slug): string
{
    $post = jhg_get_service_by_slug($slug);

    if ($post) {
        return get_permalink($post);
    }

    return home_url('/services/' . sanitize_title($slug) . '/');
}











































function jhg_checklist_repeater_items($rows): array
{
    if (! is_array($rows)) {
        return [];
    }

    $items = [];

    foreach ($rows as $row) {
        $item = trim((string) ($row['item'] ?? ''));

        if ('' !== $item) {
            $items[] = $item;
        }
    }

    return $items;
}

function jhg_gradient_checklist_resolve_columns($left, $right): array
{
    $left_items  = jhg_checklist_repeater_items($left);
    $right_items = jhg_checklist_repeater_items($right);

    return [
        'left'  => $left_items ?: $right_items,
        'right' => $right_items ?: $left_items,
    ];
}





















































function jhg_blog_dot_heading(string $text, string $tag = 'h2', string $class = 'jhg-blog-dot-heading'): void
{
    $text = rtrim(trim($text), '.');
    $tag  = tag_escape($tag);

    printf('<%1$s class="%2$s">', $tag, esc_attr($class));
    echo '<span class="jhg-blog-dot-heading-label">' . esc_html($text) . '</span>';
    echo '<span class="jhg-blog-dot-heading-dot" aria-hidden="true">.</span>';
    printf('</%s>', $tag);
}

function jhg_blog_hub_pagination_items(int $total_pages, int $current_page): array
{
    if ($total_pages <= 4) {
        return range(1, $total_pages);
    }

    if ($current_page <= 2) {
        return [1, 2, 'dots', $total_pages];
    }

    if ($current_page >= $total_pages - 1) {
        return [1, 'dots', $total_pages - 1, $total_pages];
    }

    return [1, 'dots', $current_page, 'dots', $total_pages];
}

function jhg_blog_hub_pagination(int $total_pages, int $current_page, string $base_url, string $filter): void
{
    if ($total_pages < 2) {
        return;
    }

    $items = jhg_blog_hub_pagination_items($total_pages, $current_page);

    $page_url = static function (int $page) use ($base_url, $filter): string {
        $args = ['filter' => $filter];

        if ($page > 1) {
            $args['blog_paged'] = $page;
        }

        return add_query_arg($args, $base_url);
    };

    echo '<nav class="jhg-blog-hub-pagination" aria-label="' . esc_attr__('Blog pages', 'jhg-maven') . '">';

    if ($current_page > 1) {
        printf(
            '<a class="jhg-blog-hub-pagination-nav jhg-blog-hub-pagination-prev page-link body-sm" href="%1$s">%2$s</a>',
            esc_url($page_url($current_page - 1)),
            esc_html__('<< Prev', 'jhg-maven')
        );
    }

    echo '<ul class="pagination jhg-blog-hub-pagination-list mb-0">';

    foreach ($items as $item) {
        if ('dots' === $item) {
            echo '<li class="page-item disabled"><span class="page-link body-sm">...</span></li>';
            continue;
        }

        $page = (int) $item;

        if ($page === $current_page) {
            printf(
                '<li class="page-item active" aria-current="page"><span class="page-link body-sm">%d</span></li>',
                $page
            );
            continue;
        }

        printf(
            '<li class="page-item"><a class="page-link body-sm" href="%s">%d</a></li>',
            esc_url($page_url($page)),
            $page
        );
    }

    echo '</ul>';

    if ($current_page < $total_pages) {
        printf(
            '<a class="jhg-blog-hub-pagination-nav jhg-blog-hub-pagination-next page-link body-sm" href="%1$s">%2$s</a>',
            esc_url($page_url($current_page + 1)),
            esc_html__('Next >>', 'jhg-maven')
        );
    }

    echo '</nav>';
}





function jhg_blog_single_featured_description(int $post_id): string
{
    $content = get_post_field('post_content', $post_id);

    if (is_string($content) && '' !== trim($content)) {
        $paragraphs = preg_split('/\n\s*\n/', trim(wp_strip_all_tags($content)));

        if (! empty($paragraphs[0])) {
            return wp_trim_words(trim((string) $paragraphs[0]), 42, '…');
        }
    }

    return wp_trim_words(get_the_excerpt($post_id), 42, '…');
}

function jhg_blog_landing_hero_url(): string
{
    static $resolved = null;

    if (null !== $resolved) {
        return $resolved;
    }

    $resolved = '';
    $blog_id  = jhg_ensure_blog_page_id();

    if ($blog_id) {
        $image_id = jhg_acf_image_id(jhg_copy_flexible_subfield($blog_id, 'hero', 'background_image'));
        $url      = $image_id ? wp_get_attachment_image_url($image_id, 'full') : false;

        if ($url) {
            $resolved = $url;
        }
    }

    return $resolved;
}

function jhg_blog_featured_banner_url(): string
{
    static $resolved = null;

    if (null !== $resolved) {
        return $resolved;
    }

    $resolved = '';
    $blog_id  = jhg_ensure_blog_page_id();

    if ($blog_id) {
        $image_id = jhg_acf_image_id(jhg_copy_flexible_subfield($blog_id, 'blog_hub', 'featured_banner'));
        $url      = $image_id ? wp_get_attachment_image_url($image_id, 'full') : false;

        if ($url) {
            $resolved = $url;
        }
    }

    return $resolved;
}

function jhg_blog_single_logo_rows(): array
{
    $front_id = (int) get_option('page_on_front');

    if ($front_id < 1) {
        return [];
    }

    $logos = jhg_copy_flexible_subfield($front_id, 'logo_strip', 'logos');

    return is_array($logos) ? $logos : [];
}

function jhg_blog_single_sidebar_context(int $post_id): array
{
    $socials = function_exists('get_field') ? (get_field('social_links', 'option') ?: []) : [];
    $socials = array_values(array_filter($socials, static function ($row) {
        if ('' === jhg_acf_link_url($row['url'] ?? '')) {
            return false;
        }

        $icon = strtolower((string) ($row['icon'] ?? ''));

        return str_contains($icon, 'facebook') || str_contains($icon, 'linkedin');
    }));

    return [
        'popular_query'  => jhg_blog_single_popular_query($post_id),
        'socials'        => $socials,
        'newsletter_url' => jhg_newsletter_url(),
    ];
}

function jhg_blog_single_popular_query(int $post_id): WP_Query
{
    return new WP_Query([
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 7,
        'post__not_in'        => array_values(array_unique(array_merge(jhg_blog_excluded_post_ids(), [$post_id]))),
        'ignore_sticky_posts' => true,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'no_found_rows'       => true,
    ]);
}

function jhg_blog_single_related_query(int $post_id, int $limit = 4): WP_Query
{
    $exclude = array_values(array_unique(array_merge(jhg_blog_excluded_post_ids(), [$post_id])));

    $base_args = [
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => $limit,
        'post__not_in'        => $exclude,
        'ignore_sticky_posts' => true,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'no_found_rows'       => true,
    ];

    $categories = wp_get_post_categories($post_id);

    if ($categories) {
        $category_query = new WP_Query(array_merge($base_args, [
            'category__in' => $categories,
        ]));

        if ($category_query->post_count >= min(2, $limit)) {
            return $category_query;
        }
    }

    return new WP_Query($base_args);
}

function jhg_enqueue_blog_single_assets(): void
{
    if (! is_singular('post')) {
        return;
    }

    $styles = [
        'lead-text'   => '/assets/css/modules/lead-text.css',
        'blog-links'  => '/assets/css/modules/blog-links.css',
        'blog-single' => '/assets/css/modules/blog-single.css',
    ];

    foreach ($styles as $handle => $relative) {
        if (! file_exists(get_theme_file_path($relative))) {
            continue;
        }

        wp_enqueue_style(
            'jhg-module-' . $handle,
            get_theme_file_uri($relative),
            ['jhg-base'],
            null
            // jhg_asset_version($relative)
        );
    }
}

add_action('wp_enqueue_scripts', 'jhg_enqueue_blog_single_assets', 20);

function jhg_blog_primary_category_name(int $post_id): string
{
    $categories = get_the_category($post_id);

    if (! empty($categories)) {
        return (string) $categories[0]->name;
    }

    return __('News', 'jhg-maven');
}

function jhg_blog_excluded_post_ids(): array
{
    static $ids = null;

    if (null !== $ids) {
        return $ids;
    }

    $ids = [];
    $hello = get_page_by_path('hello-world', OBJECT, 'post');

    if ($hello) {
        $ids[] = (int) $hello->ID;
    }

    return $ids;
}

function jhg_ensure_blog_page_id(): int
{
    $page = jhg_find_page_by_paths(['blog', 'blogs']);

    return $page ? (int) $page->ID : 0;
}

function jhg_contact_cf7_get_form($ref = ''): ?WPCF7_ContactForm
{
    if (! function_exists('wpcf7_contact_form')) {
        return null;
    }

    $ref = trim((string) $ref);

    if ('' !== $ref) {
        if (function_exists('wpcf7_get_contact_form_by_hash')) {
            $form = wpcf7_get_contact_form_by_hash($ref);

            if ($form instanceof WPCF7_ContactForm) {
                return $form;
            }
        }

        if (ctype_digit($ref)) {
            $form = wpcf7_contact_form((int) $ref);

            if ($form instanceof WPCF7_ContactForm) {
                return $form;
            }
        }
    }

    // $id = jhg_ensure_contact_cf7_form();

    // if ($id > 0) {
    $form = wpcf7_contact_form($id);

    if ($form instanceof WPCF7_ContactForm) {
        return $form;
    }
    // }

    return null;
}

function jhg_contact_cf7_render_shortcode($ref = '', string $html_class = ''): string
{
    $form = jhg_contact_cf7_get_form($ref);

    if (! $form) {
        return '';
    }

    $shortcode = $form->shortcode();

    if ('' !== $html_class && false === stripos($shortcode, 'html_class=')) {
        $shortcode = preg_replace('/\]$/', ' html_class="' . esc_attr($html_class) . '"]', $shortcode);
    }

    return $shortcode;
}

function jhg_contact_cf7_disable_autop(bool $autop, array $options = []): bool
{
    $form_id = (int) ($options['contact_form_id'] ?? 0);
    $jhg_id  = function_exists('jhg_ensure_contact_cf7_form') ? jhg_ensure_contact_cf7_form() : 0;

    if ($form_id > 0 && $jhg_id > 0 && $form_id === $jhg_id) {
        return false;
    }

    return $autop;
}

add_filter('wpcf7_autop_or_not', 'jhg_contact_cf7_disable_autop', 10, 2);
