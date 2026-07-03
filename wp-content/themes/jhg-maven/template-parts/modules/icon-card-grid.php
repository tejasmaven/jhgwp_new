<?php

/**
 * Module: Icon Card Grid
 */

$heading    = get_sub_field('heading');
$card_style = get_sub_field('card_style') ?: 'simple';
$cards      = get_sub_field('cards');

if (! function_exists('jhg_icon_card_grid_intersections')) {
	function jhg_icon_card_grid_intersections(int $cols, int $rows): array
	{
		if ($cols < 1 || $rows < 1) {
			return [];
		}

		$marks = [];

		for ($col = 0; $col <= $cols; $col++) {
			for ($row = 0; $row <= $rows; $row++) {
				$marks[] = [
					'left' => ($col / $cols) * 100,
					'top'  => ($row / $rows) * 100,
				];
			}
		}

		return $marks;
	}
}

$count   = is_array($cards) ? count($cards) : 0;
$rows_lg = max(1, $count > 0 ? (int) ceil($count / 3) : 1);
$rows_md = max(1, $count > 0 ? (int) ceil($count / 2) : 1);
$cols_md = 2;
$cols_lg = 3;

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

							<div class="jhg-icon-service-card-icon">
								<span class="jhg-icon-service-card-icon-ring" aria-hidden="true"></span>
								<span class="jhg-icon-service-card-icon-bg" aria-hidden="true"></span>
								<?php if ($icon && ! empty($icon['url'])) : ?>
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

				<div class="jhg-icon-card-grid-marks jhg-icon-card-grid-marks-md" aria-hidden="true">
					<?php foreach (jhg_icon_card_grid_intersections($cols_md, $rows_md) as $mark) : ?>
						<span
							class="jhg-icon-card-grid-mark"
							style="left: <?php echo esc_attr(number_format($mark['left'], 4, '.', '')); ?>%; top: <?php echo esc_attr(number_format($mark['top'], 4, '.', '')); ?>%;"
						></span>
					<?php endforeach; ?>
				</div>

				<div class="jhg-icon-card-grid-marks jhg-icon-card-grid-marks-lg" aria-hidden="true">
					<?php foreach (jhg_icon_card_grid_intersections($cols_lg, $rows_lg) as $mark) : ?>
						<span
							class="jhg-icon-card-grid-mark"
							style="left: <?php echo esc_attr(number_format($mark['left'], 4, '.', '')); ?>%; top: <?php echo esc_attr(number_format($mark['top'], 4, '.', '')); ?>%;"
						></span>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
