<?php

/**
 * Blog card (compact).
 */

$post_id = (int) ($args['post_id'] ?? 0);
$variant = sanitize_key((string) ($args['variant'] ?? 'newest'));

if ($post_id < 1) {
	return;
}

$permalink = get_permalink($post_id);
$title     = get_the_title($post_id);
$date      = get_the_date('', $post_id);
$excerpt   = wp_trim_words(get_the_excerpt($post_id), 40, '…');
$thumb     = get_the_post_thumbnail($post_id, 'medium', [
	'class'   => 'jhg-blog-card-compact-thumb-img',
	'loading' => 'lazy',
	'alt'     => '',
]);
$category  = jhg_blog_primary_category_name($post_id);
$is_popular = 'popular' === $variant;
?>
<article class="jhg-blog-card-compact<?php echo $is_popular ? ' jhg-blog-card-compact-popular' : ''; ?>">
	<div class="d-flex align-items-start gap-3 jhg-blog-card-compact-top">
		<div class="flex-grow-1 jhg-blog-card-compact-body">
			<p class="jhg-blog-card-compact-category body-xs"><?php echo esc_html($category); ?></p>
			<h4 class="jhg-blog-card-compact-title body-lg">
				<a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
			</h4>
			<?php
			$author = jhg_blog_post_author_name($post_id);

			if ($author) :
				?>
				<p class="jhg-blog-card-compact-meta body-xs">
					<?php
					printf(
						/* translators: 1: author name, 2: publish date */
						esc_html__('By %1$s on %2$s', 'jhg-maven'),
						esc_html($author),
						esc_html($date)
					);
					?>
				</p>
			<?php endif; ?>
		</div>

		<a class="jhg-blog-card-compact-thumb flex-shrink-0" href="<?php echo esc_url($permalink); ?>" tabindex="-1" aria-hidden="true">
			<?php if ($thumb) : ?>
				<?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<span class="jhg-blog-card-compact-thumb-placeholder"></span>
			<?php endif; ?>
		</a>
	</div>

	<?php if (! $is_popular && $excerpt) : ?>
		<p class="jhg-blog-card-compact-excerpt body-xs"><?php echo esc_html($excerpt); ?></p>
	<?php endif; ?>
</article>
