<?php

/**
 * Module: Text + Media
 */

$eyebrow   = get_sub_field('eyebrow');
$heading   = get_sub_field('heading');
$body      = get_sub_field('body');
$image     = get_sub_field('image');
$position  = get_sub_field('media_position') ?: 'right';
$style     = get_sub_field('style') ?: 'panel';
$checklist                = get_sub_field('checklist');
$checklist_note           = trim((string) get_sub_field('checklist_note'));
$checklist_icon_style     = sanitize_key((string) (get_sub_field('checklist_icon_style') ?: 'navy'));
$secondary_heading        = trim((string) get_sub_field('secondary_checklist_heading'));
$secondary_checklist      = get_sub_field('secondary_checklist');
$btn_text      = trim((string) get_sub_field('button_text'));
$btn_url    = get_sub_field('button_url');

$media_left = ('left' === $position);
$text_order  = $media_left ? 'order-1 order-lg-2' : 'order-1 order-lg-1';
$media_order = $media_left ? 'order-2 order-lg-1' : 'order-2 order-lg-2';

$section_classes = 'jhg-text-media jhg-section';

if ('transparent' === $style) {
	$section_classes .= ' jhg-text-media-transparent';
} elseif ('gradient' === $style) {
	$section_classes .= ' jhg-text-media-gradient';
} elseif ('band' === $style) {
	$section_classes .= ' jhg-text-media-band';
}

$text_col  = 'band' === $style ? 'col-12 col-lg-6' : 'col-12 col-lg-7';
$media_col = 'band' === $style ? 'col-12 col-lg-6' : 'col-12 col-lg-5';
$row_class = 'row align-items-center jhg-text-media-row';

if ('band' === $style) {
	$row_class .= ' jhg-text-media-band-row gy-4';
} else {
	$row_class .= ' justify-content-center';
}

$text_stack_class = 'band' === $style
	? 'jhg-text-media-text vstack jhg-text-media-band-stack'
	: 'jhg-text-media-text vstack gap-4';

$band_plain_copy = 'band' === $style && 'plain' === $checklist_icon_style && $checklist;
$media_wrap_class = 'jhg-text-media-media h-100 d-flex align-items-center';

if ('band' !== $style) {
	$media_wrap_class .= ' mx-lg-auto';
}
?>
<section class="<?php echo esc_attr($section_classes); ?>">
	<div class="container">
		<div class="jhg-text-media-panel">
			<div class="<?php echo esc_attr($row_class); ?>">
				<div class="<?php echo esc_attr($text_col); ?> <?php echo esc_attr($text_order); ?> d-flex">
					<div class="<?php echo esc_attr($text_stack_class); ?>">
						<div class="jhg-text-media-rules" aria-hidden="true">
							<span class="jhg-text-media-rule jhg-text-media-rule-long"></span>
							<span class="jhg-text-media-rule jhg-text-media-rule-short"></span>
						</div>

						<?php if ($eyebrow) : ?>
							<p class="jhg-text-media-eyebrow <?php echo 'band' === $style ? 'title-xl' : 'title-3xl'; ?>"><?php echo esc_html($eyebrow); ?></p>
						<?php endif; ?>

						<?php if ($heading) : ?>
							<h2 class="jhg-text-media-heading title-xl"><?php echo nl2br(esc_html($heading)); ?></h2>
						<?php endif; ?>

						<?php if ($body) : ?>
							<div class="jhg-text-media-body body-md"><?php echo wp_kses_post($body); ?></div>
						<?php endif; ?>

						<?php
						$checklist_class = 'jhg-checklist';

						if ('feature' === $checklist_icon_style) {
							$checklist_class .= ' jhg-checklist-feature';
						} elseif ('plain' === $checklist_icon_style) {
							$checklist_class .= ' jhg-checklist-plain';
						}
						?>

						<?php if ($band_plain_copy) : ?>
							<div class="jhg-text-media-copy body-lg">
								<?php
								$copy_lines = [];

								foreach ($checklist as $row) {
									if (! empty($row['item'])) {
										$copy_lines[] = wp_kses(nl2br(esc_html($row['item']), false), ['br' => []]);
									}
								}

								echo implode('<br><br>', $copy_lines);
								?>
							</div>
						<?php elseif ($checklist) : ?>
							<ul class="<?php echo esc_attr($checklist_class); ?> list-unstyled mb-0 w-100<?php echo 'plain' === $checklist_icon_style ? ' vstack gap-2' : ''; ?>">
								<?php foreach ($checklist as $row) : ?>
									<?php if (! empty($row['item'])) : ?>
										<li class="<?php echo 'plain' !== $checklist_icon_style ? 'row g-2 align-items-start' : ''; ?>">
											<?php if ('plain' !== $checklist_icon_style) : ?>
												<div class="col-auto">
													<span class="jhg-checklist-icon" aria-hidden="true">
														<i class="fa-solid fa-check"></i>
													</span>
												</div>
												<div class="col">
												<?php endif; ?>
												<span class="jhg-checklist-label body-lg">
													<?php
													if ('feature' === $checklist_icon_style) {
														echo jhg_checklist_item_html((string) $row['item']);
													} else {
														echo esc_html($row['item']);
													}
													?>
												</span>
												<?php if ('plain' !== $checklist_icon_style) : ?>
												</div>
											<?php endif; ?>
										</li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php
						$note_html = $checklist_note ? jhg_checklist_note_html($checklist_note) : '';
						?>
						<?php if ($note_html) : ?>
							<div class="jhg-checklist-note-wrap">
								<?php echo $note_html; ?>
							</div>
						<?php endif; ?>

						<?php if ($secondary_heading) : ?>
							<h3 class="jhg-text-media-subheading title-lg"><?php echo esc_html($secondary_heading); ?></h3>
						<?php endif; ?>

						<?php if ($secondary_checklist) : ?>
							<ul class="<?php echo esc_attr($checklist_class); ?> list-unstyled mb-0 w-100<?php echo 'plain' === $checklist_icon_style ? ' vstack gap-2' : ''; ?>">
								<?php foreach ($secondary_checklist as $row) : ?>
									<?php if (! empty($row['item'])) : ?>
										<li class="<?php echo 'plain' !== $checklist_icon_style ? 'row g-2 align-items-start' : ''; ?>">
											<?php if ('plain' !== $checklist_icon_style) : ?>
												<div class="col-auto">
													<span class="jhg-checklist-icon" aria-hidden="true">
														<i class="fa-solid fa-check"></i>
													</span>
												</div>
												<div class="col">
												<?php endif; ?>
												<span class="jhg-checklist-label body-lg">
													<?php
													if ('feature' === $checklist_icon_style) {
														echo jhg_checklist_item_html((string) $row['item']);
													} else {
														echo esc_html($row['item']);
													}
													?>
												</span>
												<?php if ('plain' !== $checklist_icon_style) : ?>
												</div>
											<?php endif; ?>
										</li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php if ($btn_text) : ?>
							<div class="jhg-text-media-cta">
								<?php
								jhg_render_button($btn_text, [
									'url'     => $btn_url ?: home_url('/contact/'),
									'variant' => 'gradient' === $style ? 'outline-white' : 'outline-red',
									'class'   => 'gradient' === $style ? 'jhg-text-media-btn-on-dark' : '',
								]);
								?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<?php if ($image && ! empty($image['url'])) : ?>
					<div class="<?php echo esc_attr($media_col); ?> <?php echo esc_attr($media_order); ?> d-flex<?php echo 'band' === $style ? ' justify-content-lg-center' : ''; ?>">
						<div class="<?php echo esc_attr($media_wrap_class); ?><?php echo 'band' === $style ? ' jhg-text-media-band-media' : ''; ?>">
							<div class="jhg-text-media-frame w-100<?php echo 'band' === $style ? ' jhg-text-media-band-frame' : ''; ?>">
								<img
									class="jhg-text-media-image"
									src="<?php echo esc_url($image['url']); ?>"
									alt="<?php echo esc_attr($image['alt']); ?>"
									loading="lazy">
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>