<?php

/**
 * Module: CTA Banner
 */

$image     = get_sub_field('image');
$heading   = get_sub_field('heading');
$body      = get_sub_field('body');
$checklist = get_sub_field('checklist');
$btn_text  = get_sub_field('button_text');
$btn_url   = get_sub_field('button_url');

$heading_html = '';
if ($heading) {
	$heading_html = esc_html($heading);
	$heading_html = preg_replace('/(future-proof)/i', '<strong>$1</strong>', $heading_html);
	$heading_html = nl2br($heading_html);
	$heading_html = wp_kses($heading_html, [
		'strong' => [],
		'br'     => [],
	]);
}
?>
<section class="jhg-cta-banner jhg-section">
	<div class="container">
		<div class="jhg-cta-banner-panel">
			<img
				class="jhg-cta-banner-corner jhg-cta-banner-corner-top"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-top.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28"
			/>
			<img
				class="jhg-cta-banner-corner jhg-cta-banner-corner-bottom"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-bottom.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28"
			/>
			<div class="row align-items-center jhg-cta-banner-row">
				<?php if ($image && ! empty($image['url'])) : ?>
					<div class="col-12 col-lg-5">
						<div class="jhg-cta-banner-media mx-lg-auto">
							<div class="jhg-cta-banner-frame">
								<img
									class="jhg-cta-banner-image"
									src="<?php echo esc_url($image['url']); ?>"
									alt="<?php echo esc_attr($image['alt']); ?>"
									loading="lazy"
								>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<div class="col-12 col-lg-7">
					<div class="jhg-cta-banner-text">
						<div class="jhg-cta-banner-rules" aria-hidden="true">
							<span class="jhg-cta-banner-rule jhg-cta-banner-rule-short"></span>
							<span class="jhg-cta-banner-rule jhg-cta-banner-rule-long"></span>
						</div>

						<?php if ($heading_html) : ?>
							<h2 class="jhg-cta-banner-heading title-3xl"><?php echo $heading_html; ?></h2>
						<?php endif; ?>

						<?php if ($body) : ?>
							<?php
							$body_html = (false !== strpos($body, '<')) ? $body : wpautop($body);
							?>
							<div class="jhg-cta-banner-body body-lg"><?php echo wp_kses_post($body_html); ?></div>
						<?php endif; ?>

						<?php if ($checklist) : ?>
							<ul class="jhg-checklist jhg-checklist-feature">
								<?php foreach ($checklist as $row) : ?>
									<?php
									$item_html = ! empty($row['item']) ? jhg_checklist_item_html((string) $row['item']) : '';
									?>
									<?php if ($item_html) : ?>
										<li>
											<span class="jhg-checklist-icon" aria-hidden="true">
												<i class="fa-solid fa-check"></i>
											</span>
											<span class="jhg-checklist-label body-lg"><?php echo $item_html; ?></span>
										</li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php if ($btn_text) : ?>
							<div>
								<?php
								jhg_render_button($btn_text, [
									'url'     => $btn_url ?: '#',
									'variant' => 'outline-white',
								]);
								?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
