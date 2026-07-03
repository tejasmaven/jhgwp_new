<?php

/**
 * Module: Contact Form
 */

$heading  = trim((string) get_sub_field('heading'));
$form_ref = get_sub_field('cf7_form_id');

if (! function_exists('jhg_contact_cf7_render_shortcode')) {
	return;
}

$shortcode = jhg_contact_cf7_render_shortcode($form_ref, 'jhg-contact-form-cf7');

if ('' === $shortcode) {
	return;
}
?>
<section class="jhg-contact-form jhg-section" id="jhg-contact-form">
	<div class="container">
		<?php if ($heading) : ?>
			<h2 class="jhg-contact-form-heading title-2xl"><?php echo esc_html($heading); ?></h2>
		<?php endif; ?>

		<div class="jhg-contact-form-body">
			<?php echo do_shortcode($shortcode); ?>
		</div>
	</div>
</section>