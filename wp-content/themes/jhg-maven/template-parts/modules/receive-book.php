<?php

/**
 * Module: Receive & Book
 */

$receive_heading = trim((string) get_sub_field('receive_heading'));
$book_heading    = trim((string) get_sub_field('book_heading'));
$receive_items   = jhg_checklist_repeater_items(get_sub_field('receive_items'));
$book_items      = jhg_checklist_repeater_items(get_sub_field('book_items'));
$image           = get_sub_field('image');
$media_position  = (string) (get_sub_field('media_position') ?: 'right');

if (! $receive_items && ! $book_items && empty($image['url'])) {
	return;
}

$media_left  = ('left' === $media_position);
$cards_order = $media_left ? 'order-2 order-lg-2' : 'order-1 order-lg-1';
$media_order = $media_left ? 'order-1 order-lg-1' : 'order-2 order-lg-2';

$cards = [];

if ($receive_heading || $receive_items) {
	$cards[] = [
		'heading' => $receive_heading ?: "What You'll Receive",
		'items'   => $receive_items,
	];
}

if ($book_heading || $book_items) {
	$cards[] = [
		'heading' => $book_heading ?: 'How to Book',
		'items'   => $book_items,
	];
}
?>
<section class="jhg-receive-book jhg-section">
	<div class="container">
		<div class="row align-items-stretch jhg-receive-book-row">
			<?php if ($cards) : ?>
				<div class="col-12 col-lg-6 <?php echo esc_attr($cards_order); ?> d-flex">
					<div class="jhg-receive-book-cards vstack w-100">
						<?php foreach ($cards as $card) : ?>
							<article class="jhg-receive-book-card">
								<div class="jhg-receive-book-card-inner vstack">
									<?php if ($card['heading']) : ?>
										<h2 class="jhg-receive-book-card-heading title-lg"><?php echo esc_html($card['heading']); ?></h2>
									<?php endif; ?>

									<?php if ($card['items']) : ?>
										<ul class="jhg-receive-book-list list-unstyled mb-0">
											<?php foreach ($card['items'] as $item) : ?>
												<li class="row g-0 align-items-start jhg-receive-book-list-item">
													<span class="col-auto jhg-receive-book-bullet" aria-hidden="true">•</span>
													<span class="col jhg-receive-book-list-label body-lg"><?php echo esc_html($item); ?></span>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if (! empty($image['url'])) : ?>
				<div class="col-12 col-lg-6 <?php echo esc_attr($media_order); ?> d-flex">
					<div class="jhg-receive-book-media w-100">
						<img
							class="jhg-receive-book-image"
							src="<?php echo esc_url($image['url']); ?>"
							alt="<?php echo esc_attr($image['alt'] ?: ($receive_heading ?: "What You'll Receive")); ?>"
							loading="lazy"
						>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
