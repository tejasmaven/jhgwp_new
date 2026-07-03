<?php

/**
 * Blog card (large).
 */

$post_id = (int) ($args['post_id'] ?? 0);

if ($post_id < 1) {
	return;
}

$permalink = get_permalink($post_id);
$title     = get_the_title($post_id);
$date      = get_the_date('', $post_id);
$thumb     = get_the_post_thumbnail($post_id, 'large', [
	'class'   => 'jhg-blog-card-large-img',
	'loading' => 'lazy',
	'alt'     => '',
]);
$category  = jhg_blog_primary_category_name($post_id);
?>
<article class="jhg-blog-card-large">
	<a class="jhg-blog-card-large-media" href="<?php echo esc_url($permalink); ?>">
		<?php if ($thumb) : ?>
			<?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php else : ?>
			<span class="jhg-blog-card-large-media-placeholder" aria-hidden="true"></span>
		<?php endif; ?>
	</a>

	<div class="jhg-blog-card-large-body">
		<p class="jhg-blog-card-large-category body-xs"><?php echo esc_html($category); ?></p>
		<h4 class="jhg-blog-card-large-title body-lg">
			<a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
		</h4>
		<p class="jhg-blog-card-large-date body-xs"><?php echo esc_html($date); ?></p>
	</div>
</article>
