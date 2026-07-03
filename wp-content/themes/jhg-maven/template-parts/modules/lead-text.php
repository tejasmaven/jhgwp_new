<?php

/**
 * Module: Lead Text
 */

$content      = get_sub_field('content');
$btn_text     = trim((string) get_sub_field('button_text'));
$btn_url      = get_sub_field('button_url');
$btn_2_text   = trim((string) get_sub_field('button_2_text'));
$btn_2_url    = get_sub_field('button_2_url');
$has_buttons  = '' !== $btn_text || '' !== $btn_2_text;
?>
<section class="jhg-lead-text jhg-section">
	<div class="container">
		<?php if ($content || $has_buttons) : ?>
			<div class="jhg-lead-text-body body-xl">
				<?php if ($content) : ?>
					<?php echo wp_kses_post($content); ?>
				<?php endif; ?>

				<?php if ($has_buttons) : ?>
					<div class="jhg-lead-text-cta<?php echo ('' !== $btn_text && '' !== $btn_2_text) ? ' jhg-lead-text-cta-dual' : ''; ?>">
						<?php if ($btn_text) : ?>
							<?php
							jhg_render_button($btn_text, [
								'url'     => $btn_url ?: home_url('/contact/'),
								'variant' => 'outline-red',
								'class'   => 'jhg-lead-text-btn',
							]);
							?>
						<?php endif; ?>

						<?php if ($btn_2_text) : ?>
							<?php
							jhg_render_button($btn_2_text, [
								'url'     => $btn_2_url ?: home_url('/contact/'),
								'variant' => 'outline-red',
								'class'   => 'jhg-lead-text-btn',
							]);
							?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>