<?php

/**
 * Testimonial card.
 */

$review = isset($args) && is_array($args) && isset($args['review']) && is_array($args['review'])
	? $args['review']
	: (isset($review) && is_array($review) ? $review : []);
$rating = max(0, min(5, (int) ($review['rating'] ?? 0)));
?>
<article class="jhg-review-card">
	<div class="jhg-review-head">
		<?php if (! empty($review['avatar']['url'])) : ?>
			<img
				class="jhg-review-avatar"
				src="<?php echo esc_url($review['avatar']['url']); ?>"
				alt="<?php echo esc_attr($review['avatar']['alt'] ?: ($review['name'] ?? '')); ?>"
				width="48"
				height="48"
				loading="lazy"
			>
		<?php else : ?>
			<span class="jhg-review-avatar jhg-review-avatar-placeholder" aria-hidden="true"></span>
		<?php endif; ?>

		<div class="jhg-review-stars title-lg" aria-label="<?php echo esc_attr(sprintf(__('%d out of 5 stars', 'jhg-maven'), $rating)); ?>">
			<?php for ($i = 1; $i <= 5; $i++) : ?>
				<i
					class="fa-star <?php echo $i <= $rating ? 'fa-solid is-filled' : 'fa-regular is-empty'; ?>"
					aria-hidden="true"
				></i>
			<?php endfor; ?>
		</div>
	</div>

	<?php if (! empty($review['quote'])) : ?>
		<blockquote class="jhg-review-quote body-md">
			&ldquo;<?php echo esc_html($review['quote']); ?>&rdquo;
		</blockquote>
	<?php endif; ?>

	<?php if (! empty($review['name'])) : ?>
		<p class="jhg-review-name body-md"><?php echo esc_html($review['name']); ?></p>
	<?php endif; ?>
</article>
