<nav class="breadcrumb-nav mb-3 d-md-inline-flex d-none" aria-label="breadcrumb">
  <ol class="breadcrumb justify-content-center mb-0">

    <li class="breadcrumb-item">
      <?php
      if (is_page_template('page-staticpage.php')): ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-black-50 text-decoration-none"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/home-icon-gray.svg" alt="Home Icon" /></a>
      <?php else: ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-white-80 text-decoration-none"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/home-icon-white.svg" alt="Home Icon" /></a>
      <?php endif; ?>
    </li>
    <?php if (is_page_template('page-dining.php') || is_page_template('page-events.php') || is_page_template('page-booking.php')): ?>
      <li class="breadcrumb-item">
        <a href="#"
          class="text-white text-decoration-none">
          Discover
        </a>
      </li>

      <!-- CURRENT PAGE -->
      <li class="breadcrumb-item active "
        aria-current="page">
        <?php the_title(); ?>
      </li>
    <?php elseif (is_page()): ?>
      <li class="breadcrumb-item active  <?php echo is_page_template('page-staticpage.php') ? 'text-black' : 'text-white'; ?>" aria-current="page"><?php the_title(); ?></li>
    <?php elseif (is_page_template('page-staticpage.php')): ?>
      <li class="breadcrumb-item">
        <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="text-black-80 text-decoration-none">Blog</a>
      </li>
      <li class="breadcrumb-item active  text-black" aria-current="page"><?php the_title(); ?></li>

    <?php elseif (is_single()): ?>
      <?php
      $blog_page = get_page_by_path('blog');

      ?>
      <li class="breadcrumb-item">
        <a href="<?php echo esc_url(get_permalink($blog_page->ID)); ?>" class="text-white-50 text-decoration-none">Blog</a>
      </li>
      <li class="breadcrumb-item active  text-white" aria-current="page"><?php the_title(); ?></li>
    <?php endif; ?>
  </ol>
</nav>