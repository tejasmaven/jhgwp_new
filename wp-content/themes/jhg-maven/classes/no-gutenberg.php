<?php
defined('ABSPATH') || die('No script kiddies please!');
class jhg_No_Gutenberg
{
    public static function init()
    {
        add_action('plugins_loaded', array(__CLASS__, 'ayudawp_load_textdomain'));
        add_action('init', array(__CLASS__, 'ayudawp_disable_gutenberg_editor'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'ayudawp_remove_gutenberg_assets'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'ayudawp_remove_gutenberg_admin_assets'));
        add_action('after_setup_theme', array(__CLASS__, 'ayudawp_disable_fse_features'));
        add_action('init', array(__CLASS__, 'ayudawp_disable_block_widgets'));
        add_action('init', array(__CLASS__, 'ayudawp_disable_block_patterns'));
        add_action('wp_dashboard_setup', array(__CLASS__, 'ayudawp_remove_dashboard_widgets'));
        add_action('init', array(__CLASS__, 'ayudawp_disable_woocommerce_blocks'));
        add_action('after_setup_theme', array(__CLASS__, 'ayudawp_remove_theme_json_support'));
        add_filter('use_block_editor_for_post_type', '__return_false', 100);
    }
    public static function ayudawp_load_textdomain()
    {
    }
    public static function ayudawp_disable_gutenberg_editor()
    {
        add_filter('use_block_editor_for_post_type', '__return_false', 100);
        if (version_compare($GLOBALS['wp_version'], '5.0-beta', '<')) {
            add_filter('gutenberg_can_edit_post_type', '__return_false');
        }
        remove_action('try_gutenberg_panel', 'wp_try_gutenberg_panel');
        remove_action('admin_menu', 'gutenberg_menu');
        remove_action('admin_init', 'gutenberg_redirect_demo');
    }
    public static function ayudawp_remove_gutenberg_assets()
    {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style');
        wp_dequeue_style('wc-blocks-vendors-style');
        wp_dequeue_style('global-styles');
        wp_dequeue_style('classic-theme-styles');
        wp_dequeue_script('wp-block-library');
        wp_dequeue_script('wp-blocks');
        wp_dequeue_script('wp-edit-post');
        wp_dequeue_script('wp-block-editor');
        if (class_exists('WooCommerce')) {
            wp_dequeue_style('wc-blocks-style');
            wp_dequeue_style('wc-blocks-vendors-style');
        }
    }
    public static function ayudawp_remove_gutenberg_admin_assets()
    {
        wp_dequeue_style('wp-block-editor');
        wp_dequeue_style('wp-edit-blocks');
        wp_dequeue_script('wp-block-editor');
        wp_dequeue_script('wp-edit-post');
        wp_dequeue_script('wp-blocks');
    }
    public static function ayudawp_disable_fse_features()
    {
        remove_theme_support('editor-color-palette');
        remove_theme_support('editor-gradient-presets');
        remove_theme_support('editor-font-sizes');
        remove_theme_support('editor-styles');
        remove_theme_support('wp-block-styles');
        remove_theme_support('align-wide');
        remove_theme_support('custom-line-height');
        remove_theme_support('custom-spacing');
        remove_theme_support('custom-units');
        remove_theme_support('link-color');
        remove_theme_support('border');
        add_filter('block_editor_settings_all', array(__CLASS__, 'ayudawp_disable_site_editor'));
    }
    public static function ayudawp_disable_site_editor($settings)
    {
        $settings['canUser'] = false;
        return $settings;
    }
    public static function ayudawp_disable_block_widgets()
    {
        add_filter('use_widgets_block_editor', '__return_false');
        add_action('widgets_init', array(__CLASS__, 'ayudawp_remove_block_widgets'));
    }
    public static function ayudawp_remove_block_widgets()
    {
        global $wp_widget_factory;

        if (isset($wp_widget_factory->widgets['WP_Widget_Block'])) {
            unregister_widget('WP_Widget_Block');
        }
    }
    public static function ayudawp_disable_block_patterns()
    {
        remove_theme_support('core-block-patterns');
        add_filter('should_load_remote_block_patterns', '__return_false');
        remove_action('enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets');
        add_action('init', function () {
            remove_all_actions('init', 11);
        }, 9);
    }
    public static function ayudawp_remove_dashboard_widgets()
    {
        remove_meta_box('try_gutenberg', 'dashboard', 'normal');
        remove_action('welcome_panel', 'wp_welcome_panel');
    }
    public static function ayudawp_disable_woocommerce_blocks()
    {
        if (! class_exists('WooCommerce')) {
            return;
        }
        add_action('wp_enqueue_scripts', array(__CLASS__, 'ayudawp_remove_woocommerce_block_assets'), 100);
        add_filter('woocommerce_enable_gutenberg_product_editor', '__return_false');
        add_filter('woocommerce_admin_features', array(__CLASS__, 'ayudawp_disable_wc_admin_features'));
        add_filter('woocommerce_feature_product_block_editor_enabled', '__return_false');
    }
    public static function ayudawp_remove_woocommerce_block_assets()
    {
        wp_dequeue_style('wc-blocks-style');
        wp_dequeue_style('wc-blocks-vendors-style');
        wp_dequeue_script('wc-blocks');
        wp_dequeue_script('wc-blocks-vendors');
        wp_dequeue_script('wc-blocks-checkout');
        wp_dequeue_script('wc-blocks-cart');
        wp_dequeue_style('wc-blocks-packages-style');
        wp_dequeue_script('wc-blocks-packages');
    }
    public static function ayudawp_disable_wc_admin_features($features)
    {
        return array_diff($features, array(
            'product-block-editor',
            'new-product-management-experience'
        ));
    }
    public static function ayudawp_remove_theme_json_support()
    {
        add_filter('wp_theme_json_data_default', array(__CLASS__, 'ayudawp_return_empty_theme_json'));
        add_filter('wp_theme_json_data_theme', array(__CLASS__, 'ayudawp_return_empty_theme_json'));
        add_filter('wp_theme_json_data_user', array(__CLASS__, 'ayudawp_return_empty_theme_json'));
        remove_filter('render_block', 'wp_render_duotone_support');
        remove_filter('render_block', 'wp_render_layout_support_flag');
        remove_filter('render_block', 'wp_render_spacing_support_flag');
    }
    public static function ayudawp_return_empty_theme_json($theme_json)
    {
        if (class_exists('WP_Theme_JSON')) {
            return new WP_Theme_JSON(array(), 'default');
        }
        return $theme_json;
    }
}
jhg_No_Gutenberg::init();
