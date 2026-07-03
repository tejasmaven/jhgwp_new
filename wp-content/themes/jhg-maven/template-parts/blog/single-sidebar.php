<?php

/**
 * Blog single: sidebar.
 */

$popular_query  = $args['popular_query'] ?? null;
$socials        = is_array($args['socials'] ?? null) ? $args['socials'] : [];
$newsletter_url = (string) ($args['newsletter_url'] ?? jhg_newsletter_url());

if ($popular_query instanceof WP_Query && $popular_query->have_posts()) :
	?>
	<section class="jhg-blog-hub-sidebar-block jhg-blog-hub-popular-section">
		<?php jhg_blog_dot_heading(__('Popular blogs', 'jhg-maven'), 'h3', 'jhg-blog-dot-heading jhg-blog-hub-block-heading'); ?>

		<div class="jhg-blog-hub-popular d-flex flex-column gap-3">
			<?php
			while ($popular_query->have_posts()) {
				$popular_query->the_post();
				get_template_part('template-parts/components/blog-card', 'compact', [
					'post_id' => get_the_ID(),
					'variant' => 'popular',
				]);
			}
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php
endif;
?>

<div class="jhg-blog-hub-sidebar-divider" aria-hidden="true">
	<span class="jhg-blog-hub-sidebar-divider-accent"></span>
</div>

<?php if ($socials) : ?>
	<section class="jhg-blog-hub-sidebar-block jhg-blog-hub-social">
		<h3 class="jhg-blog-hub-social-heading title-xl"><?php esc_html_e('Follow JHG', 'jhg-maven'); ?></h3>
		<div class="jhg-blog-hub-social-links">
			<?php foreach ($socials as $social) : ?>
				<a
					class="jhg-blog-hub-social-link"
					href="<?php echo esc_url(jhg_acf_link_url($social['url'])); ?>"
					<?php echo jhg_acf_link_attrs($social['url'], '_blank'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					aria-label="<?php echo esc_attr(jhg_social_icon_label($social['icon'] ?? '')); ?>"
				>
					<i class="<?php echo esc_attr(jhg_social_icon_classes($social['icon'] ?? '')); ?>" aria-hidden="true"></i>
				</a>
			<?php endforeach; ?>
		</div>
	</section>
<?php endif; ?>

<section class="jhg-blog-hub-sidebar-block jhg-blog-hub-newsletter">
	<p class="jhg-blog-hub-newsletter-text title-xl">
		<?php esc_html_e('Subscribe to the JHG', 'jhg-maven'); ?><br>
		<?php esc_html_e('quarterly newsletter', 'jhg-maven'); ?>
	</p>
	<a class="jhg-blog-hub-newsletter-btn body-sm" href="<?php echo esc_url($newsletter_url); ?>">
		<?php esc_html_e('Sign Up', 'jhg-maven'); ?>
	</a>
</section>
