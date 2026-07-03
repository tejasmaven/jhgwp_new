<?php

/**
 * Module: Trust Metrics
 */

$heading = get_sub_field('heading');
$items   = get_sub_field('metrics');

if (! is_array($items)) {
	$items = [];
}

$items = array_values(array_filter($items, static function ($item) {
	$value = trim((string) ($item['value'] ?? ''));
	$label = trim((string) ($item['label'] ?? ''));

	return '' !== $value || '' !== $label;
}));

if (! $heading && ! $items) {
	return;
}
?>
<section class="jhg-trust-metrics jhg-section" aria-labelledby="jhg-trust-metrics-title">
	<div class="container">
		<?php if ($heading) : ?>
			<header class="jhg-trust-metrics-header text-center">
				<h2 id="jhg-trust-metrics-title" class="jhg-trust-metrics-heading title-xl">
					<?php echo esc_html($heading); ?>
				</h2>
			</header>
		<?php endif; ?>

		<?php if ($items) : ?>
			<div class="jhg-trust-metrics-body">
				<div class="row align-items-center text-center g-0">
					<?php foreach ($items as $index => $item) : ?>
						<?php
						$value   = trim((string) ($item['value'] ?? ''));
						$label   = trim((string) ($item['label'] ?? ''));
						$count   = function_exists('jhg_trust_metric_count_parse')
							? jhg_trust_metric_count_parse($value)
							: ['animate' => false, 'to' => 0, 'suffix' => '', 'commas' => false];
						?>
						<div class="col-12 col-sm-6 col-lg-3 jhg-trust-col">
							<div class="jhg-trust-metrics-stat">
								<?php if ('' !== $value) : ?>
									<?php if ($count['animate']) : ?>
										<p
											class="jhg-trust-metrics-value jhg-trust-metrics-value-count title-display mb-0"
											data-count-to="<?php echo (int) $count['to']; ?>"
											data-count-suffix="<?php echo esc_attr($count['suffix']); ?>"
											<?php echo $count['commas'] ? 'data-count-commas="1"' : ''; ?>
										>0<?php echo esc_html($count['suffix']); ?></p>
									<?php else : ?>
										<p class="jhg-trust-metrics-value title-display mb-0"><?php echo esc_html($value); ?></p>
									<?php endif; ?>
								<?php endif; ?>

								<?php if ('' !== $label) : ?>
									<p class="jhg-trust-metrics-label body-lg mb-0">
										<?php echo wp_kses(nl2br(esc_html($label), false), ['br' => []]); ?>
									</p>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
