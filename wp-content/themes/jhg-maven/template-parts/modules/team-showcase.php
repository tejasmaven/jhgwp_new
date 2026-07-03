<?php

/**
 * Module: Team Showcase
 */

$intro    = get_sub_field('intro_heading');
$heading  = get_sub_field('heading');
$body     = get_sub_field('body');
$image    = get_sub_field('image');
$position = get_sub_field('media_position') ?: 'right';

$media_left  = ('left' === $position);
$text_order  = $media_left ? 'order-1 order-lg-2' : 'order-1 order-lg-1';
$media_order = $media_left ? 'order-2 order-lg-1' : 'order-2 order-lg-2';
?>
<section class="jhg-team-showcase jhg-section">
	<div class="container">
		<?php if ($intro) : ?>
			<p class="jhg-team-showcase-intro title-md"><?php echo esc_html($intro); ?></p>
		<?php endif; ?>

		<div class="jhg-team-showcase-panel">
			<div class="row align-items-center jhg-team-showcase-row">
				<div class="col-12 col-lg-7 <?php echo esc_attr($text_order); ?>">
					<div class="jhg-team-showcase-text">
						<div class="jhg-team-showcase-rules" aria-hidden="true">
							<span class="jhg-team-showcase-rule jhg-team-showcase-rule-long"></span>
							<span class="jhg-team-showcase-rule jhg-team-showcase-rule-short"></span>
						</div>

						<?php if ($heading) : ?>
							<h2 class="jhg-team-showcase-heading title-xl"><?php echo esc_html($heading); ?></h2>
						<?php endif; ?>

						<?php if ($body) : ?>
							<div class="jhg-team-showcase-body body-md"><?php echo wp_kses_post($body); ?></div>
						<?php endif; ?>
					</div>
				</div>

				<?php if ($image && ! empty($image['url'])) : ?>
					<div class="col-12 col-lg-5 <?php echo esc_attr($media_order); ?>">
						<div class="jhg-team-showcase-media mx-lg-auto">
							<img
								class="jhg-team-showcase-photo"
								src="<?php echo esc_url($image['url']); ?>"
								alt="<?php echo esc_attr($image['alt'] ?: $heading); ?>"
								loading="lazy"
								width="690"
								height="754"
							>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
