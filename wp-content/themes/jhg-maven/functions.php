<?php
function jhg_setup()
{
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');

  register_nav_menus([
    'primary' => __('Primary Menu', 'jhg-maven'),
    'footer'  => __('Footer Menu', 'jhg-maven'),
    'footer-bottom-menu'  => __('Footer Bottom Menu', 'jhg-maven'),
  ]);
  add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption']);
  add_theme_support('align-wide');
  add_theme_support('responsive-embeds');
  add_theme_support('editor-styles');
  add_editor_style('assets/css/style-editor.css');
}
add_action('after_setup_theme', 'jhg_setup');

if (! function_exists('jhg_post_thumbnail')) :
  /**
   * Displays an optional post thumbnail.
   */
  function jhg_post_thumbnail()
  {
    if (post_password_required() || is_attachment() || ! has_post_thumbnail()) {
      return;
    }

    if (is_singular()) :
?>
      <div class="post-thumbnail">
        <?php the_post_thumbnail('full'); ?>
      </div>
    <?php
    else :
    ?>
      <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
        <?php
        the_post_thumbnail(
          'post-thumbnail',
          array(
            'alt' => the_title_attribute(array('echo' => false)),
          )
        );
        ?>
      </a>
<?php
    endif;
  }
endif;

function remove_page_editor()
{
  remove_post_type_support('page', 'editor');
}
add_action('init', 'remove_page_editor');

add_filter('gutenberg_use_widgets_block_editor', '__return_false');
add_filter('use_widgets_block_editor', '__return_false');

require get_template_directory() . '/classes/no-gutenberg.php';

require get_template_directory() . '/inc/functions-performance.php';
require get_template_directory() . '/inc/services.php';
require get_template_directory() . '/inc/testimonials.php';
require get_template_directory() . '/inc/teams.php';
require get_template_directory() . '/inc/blogs.php';
require get_template_directory() . '/inc/custom-functions.php';

require get_template_directory() . '/inc/enqueue.php';
//require get_template_directory() . '/inc/acf-setup.php';
require get_template_directory() . '/inc/modules.php';
