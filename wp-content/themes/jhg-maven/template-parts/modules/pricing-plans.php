<?php

/**
 * Module: Pricing Plans
 */

$heading        = trim((string) get_sub_field('heading'));
$default_period = sanitize_key((string) (get_sub_field('default_period') ?: 'annual'));
$plans          = get_sub_field('plans');

if (! is_array($plans) || ! $plans) {
	return;
}

$period_labels = [
	'monthly' => __('Monthly', 'jhg-maven'),
	'annual'  => __('Annual', 'jhg-maven'),
];

$periods = [];

foreach ($plans as $plan) {
	$period = sanitize_key((string) ($plan['billing_period'] ?? ''));

	if (! $period || isset($periods[$period]) || ! isset($period_labels[$period])) {
		continue;
	}

	$periods[$period] = $period_labels[$period];
}

if (! isset($periods[$default_period]) && $periods) {
	$default_period = (string) array_key_first($periods);
}

$toggle_label = trim((string) get_sub_field('toggle_label'));
$promo_text   = trim((string) get_sub_field('promo_text'));
?>
<section class="jhg-pricing-plans jhg-section" data-default-period="<?php echo esc_attr($default_period); ?>">
	<div class="container">
		<?php if ($heading) : ?>
			<h2 class="jhg-pricing-plans-heading title-3xl"><?php echo esc_html($heading); ?></h2>
		<?php endif; ?>

		<?php if (count($periods) > 1) : ?>
			<div class="jhg-pricing-plans-toolbar">
				<div class="jhg-pricing-plans-toggle" role="tablist" aria-label="<?php esc_attr_e('Billing period', 'jhg-maven'); ?>">
					<?php if ($toggle_label) : ?>
						<span class="jhg-pricing-plans-toggle-label title-lg"><?php echo esc_html($toggle_label); ?></span>
						<span class="jhg-pricing-plans-toggle-spacer" aria-hidden="true"></span>
					<?php endif; ?>
					<?php foreach ($periods as $period_key => $period_label) : ?>
						<?php $active = ($period_key === $default_period); ?>
						<button
							type="button"
							class="jhg-pricing-plans-tab title-lg<?php echo $active ? ' is-active' : ''; ?>"
							role="tab"
							aria-selected="<?php echo $active ? 'true' : 'false'; ?>"
							data-period="<?php echo esc_attr($period_key); ?>">
							<?php echo esc_html($period_label); ?>
						</button>
					<?php endforeach; ?>
				</div>
				<?php if ($promo_text) : ?>
					<p class="jhg-pricing-plans-promo title-lg"><?php echo esc_html($promo_text); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="jhg-pricing-plans-grid">
			<?php foreach ($plans as $plan) : ?>
				<?php
				$period   = sanitize_key((string) ($plan['billing_period'] ?? 'annual'));
				$name     = trim((string) ($plan['name'] ?? ''));
				$price    = trim((string) ($plan['price'] ?? ''));
				$features = $plan['features'] ?? [];
				$accent   = sanitize_key((string) ($plan['accent'] ?? 'navy'));
				$btn_text = trim((string) ($plan['button_text'] ?? ''));
				$btn_url  = ($plan['button_url'] ?? '#');
				$hidden   = ($period !== $default_period);

				if ('' === $name || ! isset($period_labels[$period])) {
					continue;
				}

				$card_classes = ['jhg-pricing-card'];
				$is_growth    = ('red' === $accent);

				if ($is_growth) {
					$card_classes[] = 'jhg-pricing-card-growth';
				} elseif ('enterprise' === strtolower($name)) {
					$card_classes[] = 'jhg-pricing-card-wide';
				}

				$price_parts = function_exists('jhg_pricing_price_parts')
					? jhg_pricing_price_parts($price)
					: null;
				?>
				<article
					class="<?php echo esc_attr(implode(' ', $card_classes)); ?>"
					data-period="<?php echo esc_attr($period); ?>"
					<?php echo $hidden ? ' hidden' : ''; ?>>
					<div class="jhg-pricing-card-inner">
						<header class="jhg-pricing-card-package">
							<h3 class="jhg-pricing-card-name title-lg"><?php echo esc_html($name); ?></h3>

							<?php if ($price) : ?>
								<?php if (is_array($price_parts)) : ?>
									<p class="jhg-pricing-card-price">
										<span class="jhg-pricing-card-price-amount title-3xl"><?php echo esc_html($price_parts['amount']); ?></span><span class="jhg-pricing-card-price-suffix title-lg"><?php echo esc_html($price_parts['suffix']); ?></span>
									</p>
								<?php else : ?>
									<p class="jhg-pricing-card-price jhg-pricing-card-price-single title-3xl"><?php echo esc_html($price); ?></p>
								<?php endif; ?>
							<?php endif; ?>
						</header>

						<?php if (is_array($features) && $features) : ?>
							<ul class="jhg-pricing-card-features">
								<?php foreach ($features as $row) : ?>
									<?php if (! empty($row['feature'])) : ?>
										<li>
											<span class="jhg-pricing-card-check" aria-hidden="true">
												<i class="fa-solid fa-check"></i>
											</span>
											<span class="jhg-pricing-card-feature-text body-md"><?php echo esc_html($row['feature']); ?></span>
										</li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php if ($btn_text) : ?>
							<div class="jhg-pricing-card-cta">
								<?php
								jhg_render_button($btn_text, [
									'url'     => $btn_url ?: '#',
									'variant' => $is_growth ? 'red' : 'navy',
									'class'   => 'jhg-pricing-card-btn jhg-btn-block',
								]);
								?>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>