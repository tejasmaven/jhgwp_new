<?php

/**
 * Module: Contact Details
 */

$contact     = jhg_resolve_contact_details_fields();
$map_embed   = $contact['map_embed'];
$phone       = $contact['phone'];
$email       = $contact['email'];
$whatsapp_url = $contact['whatsapp_url'];
$head_office = $contact['head_office'];
$office_hours = $contact['office_hours'];

if (! $map_embed && ! $phone && ! $email && ! $head_office) {
	return;
}

$has_map  = ('' !== $map_embed);
$map_col  = $has_map ? 'col-12 col-lg-6' : '';
$card_col = $has_map ? 'col-12 col-lg-6' : 'col-12 col-lg-8 mx-lg-auto';
?>
<section class="jhg-contact-details jhg-section">
	<div class="container">
		<div class="row g-4 g-lg-5 align-items-stretch justify-content-center jhg-contact-details-row">
			<?php if ($has_map) : ?>
				<div class="<?php echo esc_attr($map_col); ?> d-flex">
					<div class="jhg-contact-details-map w-100">
						<iframe
							class="jhg-contact-details-map-iframe"
							src="<?php echo esc_url($map_embed); ?>"
							title="<?php esc_attr_e('JHG office location map', 'jhg-maven'); ?>"
							loading="lazy"
							referrerpolicy="no-referrer-when-downgrade"
							allowfullscreen
						></iframe>
					</div>
				</div>
			<?php endif; ?>

			<div class="<?php echo esc_attr($card_col); ?> d-flex">
			<article class="jhg-contact-details-card w-100">
				<div class="jhg-contact-details-accent" aria-hidden="true"></div>

				<div class="jhg-contact-details-copy body-lg">
					<?php if ($phone) : ?>
						<p class="jhg-contact-details-block">
							<span class="jhg-contact-details-heading"><?php esc_html_e('Office Phone:', 'jhg-maven'); ?></span><br>
							<a class="jhg-contact-details-link" href="<?php echo esc_url('tel:' . preg_replace('/\s+/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
						</p>
					<?php endif; ?>

					<?php if ($email) : ?>
						<p class="jhg-contact-details-block">
							<span class="jhg-contact-details-heading"><?php esc_html_e('Email (General Enquiries):', 'jhg-maven'); ?></span><br>
							<a class="jhg-contact-details-link" href="<?php echo esc_url('mailto:' . $email); ?>"><?php echo esc_html($email); ?></a>
						</p>
					<?php endif; ?>

					<?php if ($whatsapp_url) : ?>
						<p class="jhg-contact-details-block">
							<span class="jhg-contact-details-heading"><?php esc_html_e('WhatsApp:', 'jhg-maven'); ?></span><br>
							<a class="jhg-contact-details-whatsapp-link" href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Click here', 'jhg-maven'); ?></a><span class="jhg-contact-details-text"><?php esc_html_e(' to message us on WhatsApp', 'jhg-maven'); ?></span>
						</p>
					<?php endif; ?>

					<?php if ($head_office) : ?>
						<p class="jhg-contact-details-block">
							<span class="jhg-contact-details-heading"><?php esc_html_e('Head Office', 'jhg-maven'); ?></span><br>
							<span class="jhg-contact-details-text"><?php echo nl2br(esc_html($head_office)); ?></span>
						</p>
					<?php endif; ?>

					<?php if ($office_hours) : ?>
						<p class="jhg-contact-details-block">
							<span class="jhg-contact-details-heading"><?php esc_html_e('Office Hours:', 'jhg-maven'); ?></span><br>
							<span class="jhg-contact-details-text"><?php echo nl2br(esc_html($office_hours)); ?></span>
						</p>
					<?php endif; ?>
				</div>
			</article>
			</div>
		</div>
	</div>
</section>
