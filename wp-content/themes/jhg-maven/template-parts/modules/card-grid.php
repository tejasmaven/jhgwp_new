<?php

/**
 * Module: Card Grid
 */

$heading = get_sub_field('heading');
$sub_heading = get_sub_field('sub_heading');
$cards   = get_sub_field('cards');

if (! function_exists('jhg_card_grid_marks')) {
	function jhg_card_grid_marks(int $cols, int $rows): array
	{
		if ($cols < 2 || $rows < 1) {
			return [];
		}

		$marks = [];

		for ($col = 2; $col <= $cols; $col++) {
			$marks[] = ['col' => $col, 'row' => 1, 'place' => 'start start', 'bottom' => false];

			for ($row = 2; $row <= $rows; $row++) {
				$marks[] = ['col' => $col, 'row' => $row, 'place' => 'start start', 'bottom' => false];
			}

			$marks[] = ['col' => $col, 'row' => $rows, 'place' => 'end start', 'bottom' => true];
		}

		return $marks;
	}
}

$count = is_array($cards) ? count($cards) : 0;
$rows_lg = $count > 0 ? (int) ceil($count / 3) : 0;
$rows_md = $count > 0 ? (int) ceil($count / 2) : 0;
?>
<section class="jhg-card-grid jhg-section">
	<div class="container">
		<div class="jhg-card-grid-panel">
			<img
				class="jhg-card-grid-corner jhg-card-grid-corner-top"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-top.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28" />
			<img
				class="jhg-card-grid-corner jhg-card-grid-corner-bottom"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-bottom.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28" />
			<?php if ($heading) : ?>
				<h2 class="jhg-card-grid-heading title-3xl"><?php echo esc_html($heading); ?></h2>
			<?php endif; ?>
			<?php if ($sub_heading) : ?>
				<h2 class="jhg-card-grid-heading title-xl"><?php echo esc_html($sub_heading); ?></h2>
			<?php endif; ?>

			<?php if ($cards) : ?>
				<div
					class="jhg-card-grid-cells"
					style="--jhg-grid-cols-md: 2; --jhg-grid-rows-md: <?php echo esc_attr((string) max(1, $rows_md)); ?>; --jhg-grid-cols-lg: 3; --jhg-grid-rows-lg: <?php echo esc_attr((string) max(1, $rows_lg)); ?>;">
					<div class="jhg-card-grid-row">
						<?php foreach ($cards as $card) : ?>
							<div class="jhg-card-grid-col">
								<article class="jhg-service-card">
									<span class="jhg-service-card-accent" aria-hidden="true"></span>

									<?php if (! empty($card['title'])) : ?>
										<h3 class="jhg-service-card-title title-lg"><?php echo wp_kses_post($card['title']); ?></h3>
									<?php endif; ?>

									<?php if (! empty($card['description'])) : ?>
										<p class="jhg-service-card-desc body-md"><?php echo wp_kses_post($card['description']); ?></p>
									<?php endif; ?>

									<?php if (! empty($card['button_text'])) : ?>
										<?php
										jhg_render_button($card['button_text'], [
											'url'     => $card['button_url'] ?: '#',
											'variant' => 'outline-white',
											'class'   => 'jhg-btn-block jhg-btn-with-icon',
											'icon'    => true,
										]);
										?>
									<?php endif; ?>
								</article>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="jhg-card-grid-marks jhg-card-grid-marks-lg" aria-hidden="true">
						<?php foreach (jhg_card_grid_marks(3, $rows_lg) as $mark) : ?>
							<span
								class="jhg-card-grid-mark<?php echo ! empty($mark['bottom']) ? ' jhg-card-grid-mark-bottom' : ''; ?>"
								style="grid-column: <?php echo (int) $mark['col']; ?>; grid-row: <?php echo (int) $mark['row']; ?>; place-self: <?php echo esc_attr($mark['place']); ?>;"></span>
						<?php endforeach; ?>
					</div>

					<div class="jhg-card-grid-marks jhg-card-grid-marks-md" aria-hidden="true">
						<?php foreach (jhg_card_grid_marks(2, $rows_md) as $mark) : ?>
							<span
								class="jhg-card-grid-mark<?php echo ! empty($mark['bottom']) ? ' jhg-card-grid-mark-bottom' : ''; ?>"
								style="grid-column: <?php echo (int) $mark['col']; ?>; grid-row: <?php echo (int) $mark['row']; ?>; place-self: <?php echo esc_attr($mark['place']); ?>;"></span>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>