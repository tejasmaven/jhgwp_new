<?php

/**
 * Module: Workshop Cards
 */

$workshops = [];

if (have_rows('workshops')) {
	while (have_rows('workshops')) {
		the_row();

		$title = trim((string) get_sub_field('title'));
		$items = jhg_checklist_repeater_items(get_sub_field('learn_items'));
		$image = get_sub_field('image');

		if ('' === $title && ! $items && empty($image['url'])) {
			continue;
		}

		$meta_position = (string) (get_sub_field('meta_position') ?: 'before_price');

		$workshops[] = [
			'title'          => $title,
			'meta'           => trim((string) get_sub_field('meta')),
			'meta_position'  => $meta_position,
			'price'          => trim((string) get_sub_field('price')),
			'price_note'     => trim((string) get_sub_field('price_note')),
			'learn_heading'  => trim((string) get_sub_field('learn_heading')) ?: "What You'll Learn:",
			'learn_items'    => $items,
			'image'          => $image,
			'media_position' => (string) (get_sub_field('media_position') ?: 'right'),
			'button_text'    => trim((string) get_sub_field('button_text')),
			'button_url'     => get_sub_field('button_url'),
		];
	}
}

if (! $workshops) {
	return;
}
?>
<section class="jhg-workshop-cards jhg-section">
	<div class="container">
		<div class="jhg-workshop-cards-stack vstack ">
			<?php foreach ($workshops as $workshop) : ?>
				<?php
				$media_left  = ('left' === $workshop['media_position']);
				$text_order  = $media_left ? 'order-1 order-lg-2' : 'order-1 order-lg-1';
				$media_order = $media_left ? 'order-2 order-lg-1' : 'order-2 order-lg-2';
				$meta_before = $workshop['meta'] && 'before_price' === $workshop['meta_position'];
				$meta_after  = $workshop['meta'] && 'after_price' === $workshop['meta_position'];
				$learn_heading = $workshop['learn_heading'];

				if ($learn_heading && ! str_ends_with($learn_heading, ':')) {
					$learn_heading .= ':';
				}
				?>
				<article class="jhg-workshop-card">
					<div class="row align-items-center jhg-workshop-card-row">
						<div class="col-12 col-lg-6 <?php echo esc_attr($text_order); ?> d-flex">
							<div class="jhg-workshop-card-text vstack jhg-workshop-card-stack w-100">
								<div class="jhg-workshop-card-intro">
									<?php if ($workshop['title']) : ?>
										<h3 class="jhg-workshop-card-title title-xl"><?php echo esc_html($workshop['title']); ?></h3>
									<?php endif; ?>

									<?php if ($meta_before) : ?>
										<p class="jhg-workshop-card-meta body-lg"><?php echo esc_html($workshop['meta']); ?></p>
									<?php endif; ?>

									<?php if ($workshop['price']) : ?>
										<p class="jhg-workshop-card-price body-lg"><?php echo esc_html($workshop['price']); ?></p>
									<?php endif; ?>

									<?php if ($meta_after) : ?>
										<p class="jhg-workshop-card-meta body-lg"><?php echo esc_html($workshop['meta']); ?></p>
									<?php endif; ?>

									<?php if ($workshop['price_note']) : ?>
										<p class="jhg-workshop-card-price-note body-lg"><?php echo esc_html($workshop['price_note']); ?></p>
									<?php endif; ?>


								</div>

								<hr class="jhg-workshop-card-divider">

								<?php if ($workshop['learn_items']) : ?>
									<div class="jhg-workshop-card-learn w-100">
										<p class="jhg-workshop-card-learn-heading title-lg"><?php echo esc_html($learn_heading); ?></p>
										<ul class="jhg-workshop-card-list list-unstyled mb-0">
											<?php foreach ($workshop['learn_items'] as $item) : ?>
												<li class="row g-0 align-items-start jhg-workshop-card-list-item">
													<span class="col-auto jhg-workshop-card-bullet" aria-hidden="true">•</span>
													<span class="col jhg-workshop-card-list-label body-lg"><?php echo esc_html($item); ?></span>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>

								<?php if ($workshop['button_text']) : ?>
									<div class="jhg-workshop-card-cta">
										<?php
										jhg_render_button($workshop['button_text'], [
											'url'     => $workshop['button_url'] ?: home_url('/contact/'),
											'variant' => 'outline-red',
											'class'   => 'jhg-workshop-card-btn',
										]);
										?>
									</div>
								<?php endif; ?>
							</div>
						</div>

						<?php if (! empty($workshop['image']['url'])) : ?>
							<div class="col-12 col-lg-6 <?php echo esc_attr($media_order); ?> d-flex justify-content-lg-center">
								<div class="jhg-workshop-card-media w-100">
									<img
										class="jhg-workshop-card-image"
										src="<?php echo esc_url($workshop['image']['url']); ?>"
										alt="<?php echo esc_attr($workshop['image']['alt'] ?: $workshop['title']); ?>"
										loading="lazy">
								</div>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>