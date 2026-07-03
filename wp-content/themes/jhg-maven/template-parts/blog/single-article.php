<?php

/**
 * Blog single: article
 */

$post_id       = get_the_ID();
$blog_page_id  = jhg_ensure_blog_page_id();
$blog_page_url = $blog_page_id ? get_permalink($blog_page_id) : home_url('/blog/');
$category      = jhg_blog_primary_category_name($post_id);
?>
<section id="jhg-blog-single-article" class="jhg-blog-single jhg-section">
	<div class="container">
		<article class="jhg-blog-single-article">
			<header class="jhg-blog-single-header">
				<?php if ($blog_page_url) : ?>
					<a class="jhg-blog-single-back body-xs" href="<?php echo esc_url($blog_page_url); ?>">
						<?php esc_html_e('← Back to blogs', 'jhg-maven'); ?>
					</a>
				<?php endif; ?>

				<?php if ($category) : ?>
					<p class="jhg-blog-single-category body-xs"><?php echo esc_html($category); ?></p>
				<?php endif; ?>

				<?php
				$author = jhg_blog_post_author_name($post_id);

				if ($author) :
					?>
					<p class="jhg-blog-single-meta body-xs">
						<?php
						printf(
							/* translators: 1: author name, 2: publish date */
							esc_html__('By %1$s on %2$s', 'jhg-maven'),
							esc_html($author),
							esc_html(get_the_date())
						);
						?>
					</p>
				<?php endif; ?>

				<hr class="jhg-blog-single-rule" aria-hidden="true">
			</header>

			<div class="jhg-blog-single-content entry-content body-lg">
				<?php
				the_content();

				wp_link_pages([
					'before' => '<nav class="jhg-blog-single-pages body-sm" aria-label="' . esc_attr__('Article pages', 'jhg-maven') . '"><span class="jhg-blog-single-pages-label">' . esc_html__('Pages:', 'jhg-maven') . '</span>',
					'after'  => '</nav>',
				]);
				?>
			</div>
		</article>
	</div>
</section>
