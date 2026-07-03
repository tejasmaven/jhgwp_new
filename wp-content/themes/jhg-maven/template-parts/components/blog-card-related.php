<?php

/**
 * Blog card (related).
 */

$post_id = (int) ($args['post_id'] ?? 0);

if ($post_id < 1) {
	return;
}

$permalink = get_permalink($post_id);
$title     = get_the_title($post_id);
$excerpt   = wp_trim_words(get_the_excerpt($post_id), 28, '…');
?>
<article class="jhg-blog-related-card">
	<h3 class="jhg-blog-related-card-title title-xl">
		<a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
	</h3>

	<?php if ($excerpt) : ?>
		<p class="jhg-blog-related-card-excerpt body-lg"><?php echo esc_html($excerpt); ?></p>
	<?php endif; ?>

	<div class="jhg-blog-related-card-cta">
		<?php
		jhg_render_button(__('Read Now', 'jhg-maven'), [
			'url'     => $permalink,
			'variant' => 'outline-red',
			'class'   => 'jhg-blog-related-card-btn body-lg',
		]);
		?>
	</div>
</article>
