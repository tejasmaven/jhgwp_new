<?php

/**
 * Blog single: related
 */

$post_id    = get_the_ID();
$query      = jhg_blog_single_related_query($post_id, 2);
$arrow_path = get_theme_file_path('/assets/images/icon-read-arrow.svg');
$arrow_svg  = is_readable($arrow_path) ? file_get_contents($arrow_path) : '';

if (! $query->have_posts()) {
	return;
}
?>
<section class="jhg-blog-single-related jhg-section">
	<div class="container">
		<h2 class="jhg-blog-single-related-heading title-xl">
			<?php esc_html_e('Latest blogs', 'jhg-maven'); ?>
		</h2>

		<div class="row row-cols-1 row-cols-md-2 g-4 g-lg-5 justify-content-center jhg-blog-single-related-grid">
			<?php
			while ($query->have_posts()) {
				$query->the_post();
				$permalink = get_permalink();
				$excerpt   = wp_trim_words(get_the_excerpt(), 32, '…');
				?>
				<div class="col">
					<article class="jhg-blog-single-related-card">
						<h3 class="jhg-blog-single-related-card-title title-xl">
							<a href="<?php echo esc_url($permalink); ?>"><?php the_title(); ?></a>
						</h3>

						<?php if ($excerpt) : ?>
							<p class="jhg-blog-single-related-card-excerpt body-lg"><?php echo esc_html($excerpt); ?></p>
						<?php endif; ?>

						<a class="jhg-blog-link-cta body-lg jhg-blog-single-related-card-cta" href="<?php echo esc_url($permalink); ?>">
							<span class="jhg-blog-single-related-card-cta-label"><?php esc_html_e('Read Blog', 'jhg-maven'); ?></span>
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
				</div>
				<?php
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
