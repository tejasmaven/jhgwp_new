<?php
$client_logos = get_field('client_logos', 'option');
if (! $client_logos) {
	return;
}
?>
<section class="jhg-logo-strip">
	<div class="container">
		<div class="jhg-logo-strip-marquee" role="region" aria-label="<?php esc_attr_e('Client logos', 'jhg-maven'); ?>">
			<?php jhg_render_logo_marquee(array_column($client_logos, 'client_logo_listing')); ?>
		</div>

		<div class="jhg-divider jhg-logo-strip-divider" aria-hidden="true"><span></span></div>
	</div>
</section>