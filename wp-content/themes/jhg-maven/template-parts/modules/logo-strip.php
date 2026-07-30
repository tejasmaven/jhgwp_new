<?php

/**
 * Module: Logo Strip
 */

$logos = get_sub_field('logos');
if (! $logos) {
	return;
}
?>
<section class="jhg-logo-strip">
	<div class="container">
		<div class="jhg-logo-strip-marquee" role="region" aria-label="<?php esc_attr_e('Client logos', 'jhg-maven'); ?>">
			<?php jhg_render_logo_marquee(array_column($logos, 'logo')); ?>
		</div>

		<div class="jhg-divider jhg-logo-strip-divider" aria-hidden="true"><span></span></div>
	</div>
</section>
