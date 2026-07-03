<?php

/**
 * Module: Gradient Intro
 *
 * Centered copy on a gradient panel (service intro bands).
 */

$heading        = trim((string) get_sub_field('heading'));
$text_primary   = trim((string) get_sub_field('text_primary'));
?>
<section class="jhg-gradient-checklist jhg-section">
	<div class="container">
		<div class="jhg-gradient-checklist-panel">
			<img
				class="jhg-gradient-checklist-corner jhg-gradient-checklist-corner-top"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-top.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28" />
			<img
				class="jhg-gradient-checklist-corner jhg-gradient-checklist-corner-bottom"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-bottom.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28" />
			<?php if ($heading || $text_primary) : ?>
				<div class="jhg-gradient-checklist-frame">
					<div class="jhg-gradient-checklist-inner">
						<?php if ($heading) : ?>
							<h2 class="jhg-gradient-checklist-heading title-3xl"><?php echo esc_html($heading); ?></h2>
						<?php endif; ?>
						<!-- class="jhg-gradient-checklist-label body-lg" -->
						<div class="jhg-gradient-intro-copy">
							<?php if ($text_primary) : ?>
								<p class="jhg-gradient-intro-text body-xl"><?php echo wp_kses_post($text_primary); ?></p>
							<?php endif; ?>


						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>