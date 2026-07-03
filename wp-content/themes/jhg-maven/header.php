<?php

/**
 * Header: <head>, global site header (logo + primary nav + CTA), opens <main>.
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<header class="jhg-header">
		<?php get_template_part('template-parts/header/site', 'navigation'); ?>
	</header>

	<main id="jhg-main" class="jhg-main">
		<?php if (is_single() && ! is_singular('jhg_service')): ?>
			<?php get_template_part('template-parts/blog/single', 'hero'); ?>
		<?php else: ?>
			<?php get_template_part('template-parts/header/site', 'top-banner'); ?>
		<?php endif; ?>
		<?php get_template_part('template-parts/header/site', 'client-logo'); ?>