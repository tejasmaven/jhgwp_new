<?php

/**
 * Module: Blog Links
 */

$heading = trim((string) get_sub_field('heading'));
$posts_data   = get_sub_field('posts');


if (! is_array($posts_data) || ! $posts_data) {
	return;
}

$posts = array_map(function ($post) {
	return get_post($post->ID, ARRAY_A);
}, $posts_data);

$items = [];

foreach ($posts as $post_row) {
	$title = trim((string) ($post_row['post_title'] ?? ''));

	if ('' === $title) {
		continue;
	}
	$permalink = get_permalink($post_row['ID']);

	$items[] = [
		'title'      => $title,
		'url'        => $permalink,
		'link_label' => 'Read Now',
	];
}

if (! $items) {
	return;
}

$rows       = array_chunk($items, 2);
$arrow_path = get_theme_file_path('/assets/images/icon-read-arrow.svg');
$arrow_svg  = is_readable($arrow_path) ? file_get_contents($arrow_path) : '';

$render_item = static function (array $item) use ($arrow_svg): void {
?>
	<article class="jhg-blog-link-item">
		<h3 class="jhg-blog-link-title body-lg"><?php echo jhg_kses_line_breaks($item['title']); ?></h3>
		<a class="jhg-blog-link-cta body-lg" href="<?php echo esc_url($item['url']); ?>">
			<span class="jhg-blog-link-cta-label"><?php echo esc_html($item['link_label']); ?></span>
			<?php if ($arrow_svg) : ?>
				<span class="jhg-blog-link-cta-icon" aria-hidden="true">
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG asset
					echo $arrow_svg;
					?>
				</span>
			<?php endif; ?>
		</a>
	</article>
<?php
};
?>
<section class="jhg-blog-links jhg-section">
	<div class="container">
		<div class="jhg-blog-links-panel">
			<?php if ($heading) : ?>
				<h2 class="jhg-blog-links-heading title-xl"><?php echo esc_html($heading); ?></h2>
			<?php endif; ?>

			<div class="jhg-blog-links-body">
				<?php foreach ($rows as $row_index => $row_items) : ?>
					<?php if ($row_index > 0) : ?>
						<hr class="jhg-blog-links-divider">
					<?php endif; ?>

					<div class="jhg-blog-links-row">
						<?php
						foreach ($row_items as $item) {
							$render_item($item);
						}
						?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>