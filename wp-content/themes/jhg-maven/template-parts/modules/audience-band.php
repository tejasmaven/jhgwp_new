<?php

/**
 * Module: Audience Band
 */

$heading = trim((string) get_sub_field('heading'));
$line_one = trim((string) get_sub_field('line_one'));
$line_two = trim((string) get_sub_field('line_two'));

if ('' === $heading && '' === $line_one && '' === $line_two) {
	return;
}
?>
<section class="jhg-audience-band jhg-section">
	<div class="container">
		<div class="jhg-audience-band-panel d-flex flex-column justify-content-center align-items-center">
			<img
				class="jhg-audience-band-corner jhg-audience-band-corner-top"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-top.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="eager"
				decoding="async"
				width="114"
				height="28"
			/>
			<img
				class="jhg-audience-band-corner jhg-audience-band-corner-bottom"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-bottom.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="eager"
				decoding="async"
				width="114"
				height="28"
			/>
			<div class="jhg-audience-band-inner w-100">
				<div class="jhg-audience-band-text vstack text-center mx-auto">
					<?php if ($heading) : ?>
						<h2 class="jhg-audience-band-heading title-3xl"><?php echo esc_html($heading); ?></h2>
					<?php endif; ?>

					<?php if ($line_one || $line_two) : ?>
						<p class="jhg-audience-band-copy body-lg mb-0">
							<?php if ($line_one) : ?>
								<span class="jhg-audience-band-line"><?php echo esc_html($line_one); ?></span>
							<?php endif; ?>

							<?php if ($line_one && $line_two) : ?>
								<br>
							<?php endif; ?>

							<?php if ($line_two) : ?>
								<span class="jhg-audience-band-line"><?php echo esc_html($line_two); ?></span>
							<?php endif; ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
