<?php

/**
 * 404 page template.
 */

get_header();
?>
<section class="jhg-section">
	<div class="container">
		<div class="mx-auto text-center" style="max-width: 36rem;">
			<p class="jhg-eyebrow mb-3"><?php esc_html_e('Error 404', 'jhg-maven'); ?></p>
			<h1 class="jhg-heading mb-3"><?php esc_html_e('Page not found', 'jhg-maven'); ?></h1>
			<p class="mb-4">
				<?php esc_html_e('The page you are looking for may have been moved, deleted, or never existed.', 'jhg-maven'); ?>
			</p>
			<?php
			jhg_render_button(__('Back to Home', 'jhg-maven'), [
				'url'     => home_url('/'),
				'variant' => 'red',
			]);
			?>
		</div>
	</div>
</section>
<?php
get_footer();
