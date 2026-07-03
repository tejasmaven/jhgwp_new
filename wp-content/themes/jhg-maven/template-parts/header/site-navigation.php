<?php
$jhg_has_acf  = function_exists('get_field');
$jhg_logo     = $jhg_has_acf ? get_field('site_logo', 'option') : null;
$jhg_btn_text = $jhg_has_acf ? get_field('header_button_text', 'option') : '';
$jhg_btn_url  = $jhg_has_acf ? get_field('header_button_url', 'option') : null;
$header_button_url_target  = $jhg_has_acf ? get_field('header_button_url_target', 'option') : null;
?>
<nav class="navbar navbar-expand-lg jhg-navbar" aria-label="<?php esc_attr_e('Primary', 'jhg-maven'); ?>">
	<div class="container jhg-header-inner">
		<div class="jhg-header-frame">
			<a class="navbar-brand jhg-brand p-0" href="<?php echo esc_url(home_url('/')); ?>">
				<?php if ($jhg_logo && ! empty($jhg_logo['url'])) : ?>
					<img src="<?php echo esc_url($jhg_logo['url']); ?>" alt="<?php echo esc_attr($jhg_logo['alt'] ?: get_bloginfo('name')); ?>">
				<?php else : ?>
					<span class="jhg-heading h4 mb-0"><?php bloginfo('name'); ?></span>
				<?php endif; ?>
			</a>

			<span class="jhg-header-spacer d-none d-lg-block" aria-hidden="true"></span>

			<div class="jhg-header-nav d-none d-lg-flex" id="jhg-desktop-nav">
				<?php jhg_render_primary_nav('navbar-nav jhg-nav mb-0', 'desktop'); ?>
			</div>

			<span class="jhg-header-spacer d-none d-lg-block" aria-hidden="true"></span>

			<?php if ($jhg_btn_text) : ?>
				<?php
				jhg_render_button($jhg_btn_text, [
					'url'     => $jhg_btn_url ?: '#',
					'variant' => 'red',
					'class'   => 'jhg-header-cta d-none d-lg-inline-flex',
					'target'  => $header_button_url_target
				]);
				?>
			<?php endif; ?>

			<button
				class="jhg-nav-toggle d-lg-none"
				type="button"
				data-bs-toggle="offcanvas"
				data-bs-target="#jhg-mobile-nav"
				aria-controls="jhg-mobile-nav"
				aria-expanded="false"
				aria-label="<?php esc_attr_e('Open menu', 'jhg-maven'); ?>">
				<span class="jhg-nav-toggle-box" aria-hidden="true">
					<span class="jhg-nav-toggle-line"></span>
					<span class="jhg-nav-toggle-line"></span>
					<span class="jhg-nav-toggle-line"></span>
				</span>
			</button>
		</div>
	</div>
</nav>
<div class="offcanvas offcanvas-end jhg-offcanvas-nav" tabindex="-1" id="jhg-mobile-nav" aria-labelledby="jhg-mobile-nav-title">
	<div class="offcanvas-header jhg-offcanvas-header">
		<a class="jhg-offcanvas-brand jhg-brand" href="<?php echo esc_url(home_url('/')); ?>">
			<?php if ($jhg_logo && ! empty($jhg_logo['url'])) : ?>
				<img src="<?php echo esc_url($jhg_logo['url']); ?>" alt="<?php echo esc_attr($jhg_logo['alt'] ?: get_bloginfo('name')); ?>">
			<?php else : ?>
				<span class="jhg-offcanvas-brand-text"><?php bloginfo('name'); ?></span>
			<?php endif; ?>
		</a>

		<button
			type="button"
			class="jhg-nav-toggle jhg-nav-toggle-light jhg-nav-toggle-close"
			data-bs-dismiss="offcanvas"
			aria-label="<?php esc_attr_e('Close menu', 'jhg-maven'); ?>">
			<span class="jhg-nav-toggle-box" aria-hidden="true">
				<span class="jhg-nav-toggle-line"></span>
				<span class="jhg-nav-toggle-line"></span>
				<span class="jhg-nav-toggle-line"></span>
			</span>
		</button>
	</div>

	<div class="offcanvas-body jhg-offcanvas-body">
		<nav class="jhg-offcanvas-nav-wrap" aria-labelledby="jhg-mobile-nav-title">
			<p id="jhg-mobile-nav-title" class="visually-hidden"><?php esc_html_e('Primary navigation', 'jhg-maven'); ?></p>
			<?php jhg_render_primary_nav('navbar-nav jhg-nav jhg-nav-mobile flex-column', 'mobile'); ?>
		</nav>

		<?php if ($jhg_btn_text) : ?>
			<div class="jhg-offcanvas-cta">
				<?php
				jhg_render_button($jhg_btn_text, [
					'url'     => $jhg_btn_url ?: '#',
					'variant' => 'red',
					'class'   => 'jhg-offcanvas-cta-btn',
				]);
				?>
			</div>
		<?php endif; ?>
	</div>
</div>