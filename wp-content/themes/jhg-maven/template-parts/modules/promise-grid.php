<?php

/**
 * Module: Promise Grid
 */

$heading  = get_sub_field('heading');
$promises = get_sub_field('promises');
?>
<section class="jhg-promise-grid jhg-section">
	<div class="container">
		<?php if ($heading) : ?>
			<h2 class="jhg-promise-grid-heading title-md"><?php echo esc_html($heading); ?></h2>
		<?php endif; ?>

		<?php if ($promises) : ?>
			<div class="jhg-promise-grid-list">
				<?php foreach ($promises as $item) : ?>
					<?php
					$text = $item['text'] ?? '';
					$icon = $item['icon'] ?? null;

					if (! $text) {
						continue;
					}
					?>
					<article class="jhg-promise-card">
						<div class="jhg-promise-card-icon">
							<span class="jhg-promise-card-icon-ring" aria-hidden="true"></span>
							<span class="jhg-promise-card-icon-bg" aria-hidden="true"></span>
							<?php if ($icon && ! empty($icon['url'])) : ?>
								<img src="<?php echo esc_url($icon['url']); ?>" alt="" loading="lazy">
							<?php endif; ?>
						</div>

						<p class="jhg-promise-card-text body-xl"><?php echo esc_html($text); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
