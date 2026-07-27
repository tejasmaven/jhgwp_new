<?php

/**
 * Module: Stats Band
 */

$band_style = get_sub_field('band_style') ?: 'feature';
$heading    = get_sub_field('section_heading');
$image      = get_sub_field('feature_image');
$lines      = get_sub_field('stat_lines');
$btn_text   = get_sub_field('button_text');
$btn_url    = get_sub_field('button_url');
$cards      = get_sub_field('cards');

if ('metrics' === $band_style) :
	?>
	<section class="jhg-stats-band jhg-stats-band-metrics jhg-section">
		<div class="container">
			<?php if ($heading) : ?>
				<h2 class="jhg-stats-metrics-heading title-3xl"><?php echo esc_html($heading); ?></h2>
			<?php endif; ?>

			<?php if ($cards) : ?>
				<div class="row jhg-stats-metrics-row">
					<?php foreach ($cards as $card) : ?>
						<div class="col-6 col-lg-3">
							<article class="jhg-stats-metric">
								<?php if (! empty($card['value'])) : ?>
									<div class="jhg-stats-metric-value title-display"><?php echo esc_html($card['value']); ?></div>
								<?php endif; ?>
								<?php if (! empty($card['label'])) : ?>
									<div class="jhg-stats-metric-label body-sm"><?php echo esc_html($card['label']); ?></div>
								<?php endif; ?>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return;
endif;

$has_image = $image && ! empty($image['url']);
$text_col  = $has_image ? 'col-12 col-lg-7' : 'col-12';
?>
<section class="jhg-stats-band jhg-section">
	<div class="container">
		<div class="jhg-stats-band-panel">
			<img
				class="jhg-stats-band-corner jhg-stats-band-corner-top"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-top.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28"
			/>
			<img
				class="jhg-stats-band-corner jhg-stats-band-corner-bottom"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-bottom.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28"
			/>
			<div class="row align-items-center jhg-stats-band-feature-row">
				<?php if ($has_image) : ?>
					<div class="col-12 col-lg-5">
						<div class="jhg-stats-band-media mx-lg-auto">
							<div class="jhg-stats-band-frame">
								<div class="jhg-stats-band-image-clip">
									<img
										class="jhg-stats-band-image"
										src="<?php echo esc_url($image['url']); ?>"
										alt="<?php echo esc_attr($image['alt']); ?>"
										loading="lazy"
									>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<div class="<?php echo esc_attr($text_col); ?>">
					<div class="jhg-stats-band-text">
						<div class="jhg-stats-band-rules" aria-hidden="true">
							<span class="jhg-stats-band-rule jhg-stats-band-rule-short"></span>
							<span class="jhg-stats-band-rule jhg-stats-band-rule-long"></span>
						</div>

						<?php if ($lines) : ?>
							<div class="jhg-stats-band-lines">
								<?php foreach ($lines as $row) : ?>
									<?php if (! empty($row['line'])) : ?>
										<div class="jhg-stats-band-line title-3xl"><?php echo esc_html($row['line']); ?></div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<?php if ($btn_text) : ?>
							<div>
								<?php
								jhg_render_button($btn_text, [
									'url'     => $btn_url ?: '#',
									'variant' => 'outline-white',
									'class'   => 'jhg-stats-band-btn',
								]);
								?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<?php if ($cards) : ?>
				<div class="jhg-stats-band-cards">
					<div class="row jhg-stats-band-cards-row">
						<?php foreach ($cards as $card) : ?>
							<div class="col-12 col-md-6 col-lg-4">
								<article class="jhg-stat-card h-100">
									<?php if (! empty($card['image']['url'])) : ?>
										<div class="jhg-stat-card-image-clip">
											<img
												class="jhg-stat-card-img"
												src="<?php echo esc_url($card['image']['url']); ?>"
												alt="<?php echo esc_attr($card['image']['alt']); ?>"
												loading="lazy"
											>
										</div>
									<?php endif; ?>
									<div class="jhg-stat-card-body">
										<?php
										$title = trim(($card['value'] ? $card['value'] . ' ' : '') . ($card['label'] ?? ''));
										?>
										<?php if ($title) : ?>
											<div class="jhg-stat-card-title body-md"><?php echo esc_html($title); ?></div>
										<?php endif; ?>
									</div>
								</article>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
