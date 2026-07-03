<?php

/**
 * Blog single: featured
 */

$post_id     = get_the_ID();
$excerpt     = trim(get_the_excerpt());
$description = jhg_blog_single_featured_description($post_id);
?>
<section class="jhg-blog-single-featured jhg-section">
	<div class="container">
		<div class="jhg-blog-single-featured-panel">
			<img
				class="jhg-blog-single-featured-corner jhg-blog-single-featured-corner-top"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-top.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="eager"
				decoding="async"
				width="114"
				height="28"
			/>
			<img
				class="jhg-blog-single-featured-corner jhg-blog-single-featured-corner-bottom"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-bottom.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="eager"
				decoding="async"
				width="114"
				height="28"
			/>
			<div class="jhg-blog-single-featured-overlay" aria-hidden="true"></div>

			<div class="jhg-blog-single-featured-content">
				<h2 class="jhg-blog-single-featured-title title-3xl"><?php the_title(); ?></h2>

				<?php if ($excerpt) : ?>
					<p class="jhg-blog-single-featured-subtitle body-lg">
						<span class="jhg-blog-single-featured-subtitle-label"><?php esc_html_e('Subheading:', 'jhg-maven'); ?></span>
						<?php echo esc_html($excerpt); ?>
					</p>
				<?php endif; ?>

				<?php if ($description) : ?>
					<p class="jhg-blog-single-featured-description body-md"><?php echo esc_html($description); ?></p>
				<?php endif; ?>

				<div class="jhg-blog-single-featured-cta">
					<?php
					jhg_render_button(__('Read Full Blog Here', 'jhg-maven'), [
						'url'     => '#jhg-blog-single-article',
						'variant' => 'outline-white',
						'class'   => 'jhg-blog-single-featured-btn body-md',
					]);
					?>
				</div>
			</div>
		</div>
	</div>
</section>
