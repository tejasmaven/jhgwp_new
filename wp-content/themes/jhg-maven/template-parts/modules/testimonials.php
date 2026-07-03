<?php

/**
 * Module: Testimonials
 */

$heading  = get_sub_field('heading');
$intro    = get_sub_field('intro');
$selected = get_sub_field('testimonials');
$reviews  = jhg_get_selected_testimonial_reviews($selected);

if (! $reviews) {
	return;
}

$args          = isset($args) && is_array($args) ? $args : [];
$index         = isset($args['index']) ? (int) $args['index'] : 0;
$uid           = 'jhg-testimonials-' . $index;
$desktop_cards = ! empty(get_sub_field('show_three_desktop')) ? 3 : 2;
?>
<section class="jhg-testimonials jhg-section" aria-labelledby="<?php echo esc_attr($uid); ?>-heading">
	<div class="container">
		<?php if ($heading) : ?>
			<h2 id="<?php echo esc_attr($uid); ?>-heading" class="jhg-testimonials-heading title-md">
				<?php echo esc_html($heading); ?>
			</h2>
		<?php endif; ?>

		<?php if ($intro) : ?>
			<p class="jhg-testimonials-intro body-xl"><?php echo esc_html($intro); ?></p>
		<?php endif; ?>

		<div
			class="jhg-testimonials-slider"
			data-desktop-cards="<?php echo esc_attr((string) $desktop_cards); ?>"
		>
			<button
				class="jhg-testimonials-nav jhg-testimonials-prev"
				type="button"
				aria-label="<?php esc_attr_e('Previous testimonial', 'jhg-maven'); ?>"
			>
				<i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
			</button>

			<div class="jhg-testimonials-track">
				<div class="swiper jhg-testimonials-swiper">
					<div class="swiper-wrapper">
						<?php foreach ($reviews as $review) : ?>
							<div class="swiper-slide">
								<?php get_template_part('template-parts/components/testimonial-card', null, ['review' => $review]); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<button
				class="jhg-testimonials-nav jhg-testimonials-next"
				type="button"
				aria-label="<?php esc_attr_e('Next testimonial', 'jhg-maven'); ?>"
			>
				<i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
			</button>

			<div class="jhg-testimonials-pagination swiper-pagination" aria-hidden="true"></div>
		</div>
	</div>
</section>
