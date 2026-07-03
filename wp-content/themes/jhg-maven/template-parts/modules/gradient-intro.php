<?php

/**
 * Module: Gradient Intro
 *
 * Centered copy on a gradient panel (service intro bands).
 */

$heading        = trim((string) get_sub_field('heading'));
$text_primary   = trim((string) get_sub_field('text_primary'));
$text_secondary = trim((string) get_sub_field('text_secondary'));
?>
<section class="jhg-gradient-intro jhg-section">
	<div class="container">
		<div class="jhg-gradient-intro-panel">
			<img
				class="jhg-gradient-intro-corner jhg-gradient-intro-corner-top"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-top.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28" />
			<img
				class="jhg-gradient-intro-corner jhg-gradient-intro-corner-bottom"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-bottom.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28" />
			<?php if ($heading || $text_primary || $text_secondary) : ?>
				<div class="jhg-gradient-intro-inner">
					<div class="jhg-gradient-intro-text-frame">
						<div class="jhg-gradient-intro-rules" aria-hidden="true">
							<span class="jhg-gradient-intro-rule jhg-gradient-intro-rule-long"></span>
							<span class="jhg-gradient-intro-rule jhg-gradient-intro-rule-short"></span>
						</div>

						<?php if ($heading) : ?>
							<h2 class="jhg-gradient-intro-heading title-xl"><?php echo esc_html($heading); ?></h2>
						<?php endif; ?>

						<div class="jhg-gradient-intro-copy">
							<?php if ($text_primary) : ?>
								<p class="jhg-gradient-intro-text body-xl"><?php echo wp_kses_post($text_primary); ?></p>
							<?php endif; ?>

							<?php if ($text_secondary) : ?>
								<p class="jhg-gradient-intro-text body-xl"><?php echo wp_kses_post($text_secondary); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>