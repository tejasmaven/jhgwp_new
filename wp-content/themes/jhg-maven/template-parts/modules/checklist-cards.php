<?php

/**
 * Module: Checklist Cards
 */

$column_layout = (string) get_sub_field('column_layout');

if ('' === $column_layout) {
	$column_layout = 'two_column';
}

$cards = [];

if (have_rows('cards')) {
	while (have_rows('cards')) {
		the_row();

		$heading = trim((string) get_sub_field('heading'));
		$items   = jhg_checklist_repeater_items(get_sub_field('items'));
		$btn     = trim((string) get_sub_field('button_text'));
		$btn_url = get_sub_field('button_url');
		$text_after_checklist = trim((string) get_sub_field('text_after_checklist'));

		if ('' === $heading && ! $items && '' === $btn) {
			continue;
		}

		$cards[] = [
			'heading'     => $heading,
			'items'       => $items,
			'button_text' => $btn,
			'button_url'  => $btn_url,
			'text_after_checklist' => $text_after_checklist,
		];
	}
}

if (! $cards) {
	return;
}

$card_col_class = 'single_column' === $column_layout
	? 'col-12 col-lg-6 mx-lg-auto'
	: 'col-12 col-lg-6';
?>
<section class="jhg-checklist-cards jhg-section">
	<div class="container">
		<div class="row jhg-checklist-cards-row gy-4 gy-lg-0 justify-content-lg-between justify-content-center">
			<?php foreach ($cards as $card) : ?>
				<?php
				$has_cta    = '' !== $card['button_text'];
				$card_class = 'jhg-checklist-cards-card flex-grow-1 d-flex flex-column';

				if ($has_cta) {
					$card_class .= ' jhg-checklist-cards-card-has-cta';
				}

				$inner_class = 'jhg-checklist-cards-inner';

				if ($has_cta) {
					$inner_class .= ' w-100';
				}

				?>
				<div class="<?php echo esc_attr($card_col_class); ?> ">
					<article class="<?php echo esc_attr($card_class); ?>">
						<div class="<?php echo esc_attr($inner_class); ?>">
							<?php if ($card['heading']) : ?>
								<h2 class="jhg-checklist-cards-heading title-xl"><?php echo wp_kses(nl2br(esc_html($card['heading']), false), ['br' => []]); ?></h2>
							<?php endif; ?>

							<?php if ($card['items']) : ?>
								<ul class="jhg-checklist">
									<?php foreach ($card['items'] as $item) : ?>
										<li>
											<span class="jhg-checklist-icon" aria-hidden="true">
												<i class="fa-solid fa-check"></i>
											</span>
											<span class="jhg-checklist-label body-lg">
												<?php echo wp_kses(nl2br(esc_html($item), false), ['br' => []]); ?>
											</span>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<?php if ($card['text_after_checklist']) : ?>
								<div class="jhg-checklist-cards-text-after-checklist body-lg">
									<?php echo wp_kses($card['text_after_checklist'], ['br' => []]); ?>
								</div>
							<?php endif; ?>
						</div>

						<?php if ($has_cta) : ?>
							<div class="jhg-checklist-cards-cta">
								<?php
								jhg_render_button($card['button_text'], [
									'url'     => $card['button_url'] ?: home_url('/contact/'),
									'variant' => 'outline-red',
									'class'   => 'jhg-checklist-cards-btn',
								]);
								?>
							</div>
						<?php endif; ?>
					</article>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>