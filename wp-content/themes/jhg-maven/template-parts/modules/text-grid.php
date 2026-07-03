<?php

/**
 * Module: Text Grid
 */

$heading = get_sub_field('heading');
$items   = get_sub_field('items');

if (! $items) {
	return;
}

$joints = function_exists('jhg_text_grid_joint_positions')
	? jhg_text_grid_joint_positions()
	: [];
?>
<section class="jhg-text-grid jhg-section">
	<div class="container">
		<div class="jhg-text-grid-panel">
			<div class="jhg-text-grid-wrap">
				<?php if ($heading) : ?>
					<div class="jhg-text-grid-cell jhg-text-grid-cell-title">
						<div class="jhg-text-grid-cell-inner">
							<h2 class="jhg-text-grid-heading title-md"><?php echo jhg_kses_line_breaks($heading); ?></h2>
							<span class="jhg-text-grid-accent" aria-hidden="true"></span>
						</div>
					</div>
				<?php endif; ?>

				<?php foreach ($items as $item) : ?>
					<?php if (! empty($item['text'])) : ?>
						<div class="jhg-text-grid-cell jhg-text-grid-cell-content">
							<div class="jhg-text-grid-cell-inner">
								<p class="jhg-text-grid-text body-md"><?php echo jhg_kses_line_breaks((string) $item['text']); ?></p>
								<span class="jhg-text-grid-accent" aria-hidden="true"></span>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php if ($joints) : ?>
					<div class="jhg-text-grid-joints" aria-hidden="true">
						<?php foreach ($joints as $joint) : ?>
							<span
								class="jhg-text-grid-joint"
								style="top: <?php echo esc_attr($joint['top']); ?>; left: <?php echo esc_attr($joint['left']); ?>;"
							></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
