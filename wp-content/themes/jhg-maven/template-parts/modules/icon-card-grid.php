<?php

/**
 * Module: Icon Card Grid
 */

$heading    = get_sub_field('heading');
$card_style = get_sub_field('card_style') ?: 'simple';
$cards      = get_sub_field('cards');

$section_class = 'jhg-icon-card-grid jhg-section';

if ('detailed' === $card_style) {
	$section_class .= ' jhg-icon-card-grid-detailed';
}
?>
<section class="<?php echo esc_attr($section_class); ?>">
	<div class="container">
		<?php if ($heading) : ?>
			<h2 class="jhg-icon-card-grid-heading title-3xl"><?php echo esc_html($heading); ?></h2>
		<?php endif; ?>

		<?php if ($cards) : ?>
			<div class="jhg-icon-card-grid-wrap">
				<div class="jhg-icon-card-grid-cells">
					<?php foreach ($cards as $card) : ?>
						<?php
						$title       = $card['title'] ?? '';
						$url         = jhg_acf_link_url($card['url'] ?? '');
						$description = trim((string) ($card['description'] ?? ''));
						$btn_text    = trim((string) ($card['button_text'] ?? ''));
						$btn_url     = $card['button_url'] ?? null;
						$icon        = $card['icon'] ?? null;
						$icon_svg    = $icon ? jhg_inline_svg_icon($icon, 'jhg-icon-service-card-svg') : '';
						$detailed   = ('detailed' === $card_style) || ('' !== $description || '' !== $btn_text);
						$card_tag   = ($url && ! $detailed) ? 'a' : 'article';
						$card_class = 'jhg-icon-service-card';

						if ($detailed) {
							$card_class .= ' jhg-icon-service-card-detailed';
						}
						?>

						<?php if ('a' === $card_tag) : ?>
							<a class="<?php echo esc_attr($card_class); ?>" href="<?php echo esc_url($url); ?>">
						<?php else : ?>
							<article class="<?php echo esc_attr($card_class); ?>">
						<?php endif; ?>

							<span class="jhg-icon-service-card-glow" aria-hidden="true"></span>

							<span class="jhg-icon-card-grid-dot jhg-icon-card-grid-dot-tl" aria-hidden="true"></span>
							<span class="jhg-icon-card-grid-dot jhg-icon-card-grid-dot-tr" aria-hidden="true"></span>
							<span class="jhg-icon-card-grid-dot jhg-icon-card-grid-dot-bl" aria-hidden="true"></span>
							<span class="jhg-icon-card-grid-dot jhg-icon-card-grid-dot-br" aria-hidden="true"></span>

							<div class="jhg-icon-service-card-icon">
								<span class="jhg-icon-service-card-icon-ring" aria-hidden="true"></span>
								<span class="jhg-icon-service-card-icon-bg" aria-hidden="true"></span>
								<?php if ('' !== $icon_svg) : ?>
									<?php echo $icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php elseif ($icon && ! empty($icon['url'])) : ?>
									<img src="<?php echo esc_url($icon['url']); ?>" alt="" loading="lazy">
								<?php endif; ?>
							</div>

							<?php if ($title) : ?>
								<h3 class="jhg-icon-service-card-title title-lg"><?php echo esc_html($title); ?></h3>
							<?php endif; ?>

							<?php if ($description) : ?>
								<div class="jhg-icon-service-card-desc body-md"><?php echo esc_html($description); ?></div>
							<?php endif; ?>

							<?php if ($btn_text) : ?>
								<div class="jhg-icon-service-card-cta">
									<?php
									jhg_render_button($btn_text, [
										'url'     => $btn_url ?: $url ?: '#',
										'variant' => 'outline-navy',
										'class'   => 'jhg-icon-service-card-btn body-sm',
									]);
									?>
								</div>
							<?php endif; ?>

							<?php if (! $detailed) : ?>
								<span class="jhg-icon-service-card-line" aria-hidden="true"></span>
							<?php endif; ?>

						<?php if ('a' === $card_tag) : ?>
							</a>
						<?php else : ?>
							</article>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
