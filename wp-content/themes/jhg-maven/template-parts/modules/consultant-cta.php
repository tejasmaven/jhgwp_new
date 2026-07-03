<?php

/**
 * Module: Consultant CTA
 */

$heading    = trim((string) get_sub_field('heading'));
$subheading = trim((string) get_sub_field('subheading'));
$btn_text   = trim((string) get_sub_field('button_text'));
$btn_url    = get_sub_field('button_url');

if ('' === $heading && '' === $subheading && '' === $btn_text) {
	return;
}
?>
<section class="jhg-consultant-cta jhg-section">
	<div class="container">
		<div class="jhg-consultant-cta-inner">
			<?php if ($heading || $subheading) : ?>
				<h2 class="jhg-consultant-cta-heading title-3xl">
					<?php if ($heading) : ?>
						<span class="jhg-consultant-cta-heading-line"><?php echo esc_html($heading); ?></span>
					<?php endif; ?>
					<?php if ($subheading) : ?>
						<br>
						<span class="jhg-consultant-cta-heading-line"><?php echo esc_html($subheading); ?></span>
					<?php endif; ?>
				</h2>
			<?php endif; ?>

			<?php if ($btn_text) : ?>
				<div class="jhg-consultant-cta-action">
					<?php
					jhg_render_button($btn_text, [
						'url'     => $btn_url ?: home_url('/contact/'),
						'variant' => 'outline-red',
						'class'   => 'jhg-consultant-cta-btn',
					]);
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>