<?php

/**
 * Module: Resource Find Grid
 */

$heading = trim((string) get_sub_field('heading'));
$items   = get_sub_field('items');

if (! is_array($items) || ! $items) {
	return;
}
?>
<section class="jhg-resource-find-grid jhg-section">
	<div class="container">
		<?php if ($heading) : ?>
			<h2 class="jhg-resource-find-grid-heading title-lg"><?php echo esc_html($heading); ?></h2>
		<?php endif; ?>

		<div class="jhg-resource-find-grid-cells">
			<?php foreach ($items as $item) : ?>
				<?php
				$label = trim((string) ($item['label'] ?? ''));
				$icon  = $item['icon'] ?? null;

				if ('' === $label && empty($icon['url'])) {
					continue;
				}
				?>
				<article class="jhg-resource-find-card">
					<div class="jhg-resource-find-card-icon" aria-hidden="true">
						<span class="jhg-resource-find-card-icon-ring"></span>
						<span class="jhg-resource-find-card-icon-bg"></span>
						<?php if (! empty($icon['url'])) : ?>
							<img src="<?php echo esc_url($icon['url']); ?>" alt="" loading="lazy">
						<?php endif; ?>
					</div>
					<?php if ($label) : ?>
						<p class="jhg-resource-find-card-label body-lg mb-0"><?php echo jhg_kses_line_breaks($label); ?></p>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
