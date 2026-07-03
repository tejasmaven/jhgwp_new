<?php

/**
 * Blog single: logo strip
 */

$logos = jhg_blog_single_logo_rows();

if (! $logos) {
	return;
}
?>
<section class="jhg-logo-strip">
	<div class="container">
		<div class="jhg-logo-strip-marquee" role="region" aria-label="<?php esc_attr_e('Client logos', 'jhg-maven'); ?>">
			<div class="jhg-logo-strip-track">
				<ul class="jhg-logo-strip-list">
					<?php foreach ($logos as $row) : ?>
						<?php $logo = $row['logo'] ?? null; ?>
						<?php if (is_array($logo) && ! empty($logo['url'])) : ?>
							<li class="jhg-logo-strip-item">
								<img src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($logo['alt'] ?? ''); ?>" loading="lazy">
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>

				<ul class="jhg-logo-strip-list" aria-hidden="true">
					<?php foreach ($logos as $row) : ?>
						<?php $logo = $row['logo'] ?? null; ?>
						<?php if (is_array($logo) && ! empty($logo['url'])) : ?>
							<li class="jhg-logo-strip-item">
								<img src="<?php echo esc_url($logo['url']); ?>" alt="" loading="lazy">
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<div class="jhg-divider jhg-logo-strip-divider" aria-hidden="true"><span></span></div>
	</div>
</section>
