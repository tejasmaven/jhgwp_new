<?php

/**
 * Module: Dual Feature
 */

$left_image           = get_sub_field('left_image');
$left_heading         = get_sub_field('left_heading');
$left_list_items      = jhg_dual_feature_normalize_list_items(get_sub_field('left_list_items'));
$left_list_two_columns = ! empty(get_sub_field('left_list_two_columns'));
$right_image          = get_sub_field('right_image');
$right_heading        = get_sub_field('right_heading');
$right_list_items     = jhg_dual_feature_normalize_list_items(get_sub_field('right_list_items'));
$right_list_two_columns = ! empty(get_sub_field('right_list_two_columns'));
$btn_text             = get_sub_field('button_text');
$btn_url              = get_sub_field('button_url');
?>
<section class="jhg-dual-feature jhg-section">
	<div class="container">
		<div class="jhg-dual-feature-panel">
			<img
				class="jhg-dual-feature-corner jhg-dual-feature-corner-top"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-top.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28"
			/>
			<img
				class="jhg-dual-feature-corner jhg-dual-feature-corner-bottom"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-bottom.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28"
			/>
			<div class="jhg-dual-feature-grid">
				<article class="jhg-dual-feature-card">
					<div class="jhg-dual-feature-card-inner">
						<?php if ($left_image && ! empty($left_image['url'])) : ?>
							<div class="jhg-dual-feature-media">
								<img
									class="jhg-dual-feature-image"
									src="<?php echo esc_url($left_image['url']); ?>"
									alt="<?php echo esc_attr($left_image['alt'] ?: $left_heading); ?>"
									width="500"
									height="330"
									loading="lazy"
								>
							</div>
						<?php endif; ?>

						<?php if ($left_heading) : ?>
							<h2 class="jhg-dual-feature-heading title-xl"><?php echo esc_html($left_heading); ?></h2>
						<?php endif; ?>

						<?php if ($left_list_items) : ?>
							<div class="jhg-dual-feature-body body-md">
								<?php jhg_dual_feature_render_list($left_list_items, $left_list_two_columns); ?>
							</div>
						<?php endif; ?>
					</div>
				</article>

				<article class="jhg-dual-feature-card">
					<div class="jhg-dual-feature-card-inner">
						<?php if ($right_image && ! empty($right_image['url'])) : ?>
							<div class="jhg-dual-feature-media">
								<img
									class="jhg-dual-feature-image"
									src="<?php echo esc_url($right_image['url']); ?>"
									alt="<?php echo esc_attr($right_image['alt'] ?: $right_heading); ?>"
									width="500"
									height="330"
									loading="lazy"
								>
							</div>
						<?php endif; ?>

						<?php if ($right_heading) : ?>
							<h2 class="jhg-dual-feature-heading title-xl"><?php echo esc_html($right_heading); ?></h2>
						<?php endif; ?>

						<?php if ($right_list_items) : ?>
							<div class="jhg-dual-feature-body body-md">
								<?php jhg_dual_feature_render_list($right_list_items, $right_list_two_columns); ?>
							</div>
						<?php endif; ?>
					</div>
				</article>
			</div>
		</div>

		<?php if ($btn_text) : ?>
			<div class="jhg-dual-feature-cta">
				<?php
				jhg_render_button($btn_text, [
					'url'     => $btn_url ?: '#',
					'variant' => 'outline-red',
					'class'   => 'jhg-btn-min-256',
				]);
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
