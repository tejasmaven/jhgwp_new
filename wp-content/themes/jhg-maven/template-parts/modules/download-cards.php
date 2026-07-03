<?php

/**
 * Module: Download Cards
 */

$heading = trim((string) get_sub_field('heading'));
$cards   = get_sub_field('cards');

if (! is_array($cards) || ! $cards) {
	return;
}
?>
<section class="jhg-download-cards jhg-section">
	<div class="container">
		<?php if ($heading) : ?>
			<h2 class="jhg-download-cards-heading title-xl"><?php echo esc_html($heading); ?></h2>
		<?php endif; ?>

		<div class="row g-4 jhg-download-cards-row justify-content-center">
			<?php foreach ($cards as $card) : ?>
				<?php
				$title       = trim((string) ($card['title'] ?? ''));
				$description = trim((string) ($card['description'] ?? ''));
				$btn_text    = trim((string) ($card['button_text'] ?? '')) ?: 'Download Now';
				$btn_link    = $card['button_url'] ?? null;
				$btn_url     = jhg_acf_link_url($btn_link, '#');

				if ('' === $title && '' === $description) {
					continue;
				}
				?>
				<div class="col-12 col-md-6 col-xl-4">
					<article class="jhg-download-card h-100">
						<?php if ($title) : ?>
							<h3 class="jhg-download-card-title title-lg"><?php echo jhg_kses_line_breaks($title); ?></h3>
						<?php endif; ?>

						<?php if ($description) : ?>
							<p class="jhg-download-card-desc body-lg mb-0"><?php echo jhg_kses_line_breaks($description); ?></p>
						<?php endif; ?>

						<?php if ($btn_text) : ?>
							<a class="jhg-download-card-btn" href="<?php echo esc_url($btn_url ?: '#'); ?>"<?php echo jhg_acf_link_attrs($btn_link); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<span class="jhg-download-card-btn-label title-lg"><?php echo esc_html($btn_text); ?></span>
								<span class="jhg-download-card-btn-icon" aria-hidden="true">
									<?php
									$icon_path = get_theme_file_path('/assets/images/icon-download.svg');

									if (is_readable($icon_path)) {
										// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG asset
										echo file_get_contents($icon_path);
									}
									?>
								</span>
							</a>
						<?php endif; ?>
					</article>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
