<?php

/**
 * Module: Founder Showcase
 */

$eyebrow    = get_sub_field('eyebrow');
$subheading = get_sub_field('subheading');
$body       = get_sub_field('body');
$quote      = get_sub_field('quote');
$image      = get_sub_field('image');
?>
<section class="jhg-founder-showcase jhg-section">
	<div class="container">
		<div class="jhg-founder-showcase-panel">
			<img
				class="jhg-founder-showcase-corner jhg-founder-showcase-corner-top"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-top.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28"
			/>
			<img
				class="jhg-founder-showcase-corner jhg-founder-showcase-corner-bottom"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-bottom.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28"
			/>
			<div class="row align-items-center jhg-founder-showcase-row">
				<div class="col-12 col-lg-7">
					<div class="jhg-founder-showcase-text">
						<div class="jhg-founder-showcase-rules" aria-hidden="true">
							<span class="jhg-founder-showcase-rule jhg-founder-showcase-rule-long"></span>
							<span class="jhg-founder-showcase-rule jhg-founder-showcase-rule-short"></span>
						</div>

						<?php if ($eyebrow) : ?>
							<p class="jhg-founder-showcase-eyebrow title-3xl"><?php echo esc_html($eyebrow); ?></p>
						<?php endif; ?>

						<div class="jhg-founder-showcase-content body-md">
							<?php if ($subheading) : ?>
								<p class="jhg-founder-showcase-name"><?php echo esc_html($subheading); ?></p>
							<?php endif; ?>

							<?php if ($body) : ?>
								<div class="jhg-founder-showcase-body"><?php echo wp_kses_post($body); ?></div>
							<?php endif; ?>

							<?php if ($quote) : ?>
								<p class="jhg-founder-showcase-quote"><?php echo esc_html($quote); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<?php if ($image && ! empty($image['url'])) : ?>
					<div class="col-12 col-lg-5">
						<div class="jhg-founder-showcase-media mx-lg-auto">
							<div class="jhg-founder-showcase-frame" aria-hidden="true"></div>
							<img
								class="jhg-founder-showcase-image"
								src="<?php echo esc_url($image['url']); ?>"
								alt="<?php echo esc_attr($image['alt'] ?: $subheading); ?>"
								loading="lazy"
							>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
