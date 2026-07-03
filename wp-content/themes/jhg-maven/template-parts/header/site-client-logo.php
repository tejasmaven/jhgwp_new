<?php
$client_logos = get_field('client_logos', 'option');
if (! $client_logos) {
	return;
}
?>
<section class="jhg-logo-strip">
	<div class="container">
		<div class="jhg-logo-strip-marquee" role="region" aria-label="<?php esc_attr_e('Client logos', 'jhg-maven'); ?>">
			<div class="jhg-logo-strip-track">
				<ul class="jhg-logo-strip-list">
					<?php foreach ($client_logos as $row) : ?>
						<?php $logo = $row['client_logo_listing']; ?>
						<?php if ($logo && ! empty($logo['url'])) : ?>
							<li class="jhg-logo-strip-item">
								<img src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>" loading="lazy">
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>

				<!-- Duplicate for seamless looping -->
				<ul class="jhg-logo-strip-list" aria-hidden="true">
					<?php foreach ($client_logos as $row) : ?>
						<?php $logo = $row['client_logo_listing']; ?>
						<?php if ($logo && ! empty($logo['url'])) : ?>
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