<?php

/**
 * Module: Blog Hub
 */

$featured_banner = get_sub_field('featured_banner');
$featured_link   = get_sub_field('featured_url');
$newsletter_link = get_sub_field('newsletter_url');
$featured_url    = jhg_acf_link_url($featured_link);
$newsletter_url  = jhg_acf_link_url($newsletter_link);

if ('' === $newsletter_url) {
	$newsletter_url = jhg_newsletter_url();
}

$banner_id   = is_array($featured_banner) ? (int) ($featured_banner['ID'] ?? 0) : (int) $featured_banner;
$banner_src  = $banner_id ? wp_get_attachment_image_url($banner_id, 'full') : '';
$banner_href = $featured_url ?: '#';

$filter = isset($_GET['filter']) ? sanitize_key(wp_unslash((string) $_GET['filter'])) : 'newest'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

if ('latest' === $filter) {
	$filter = 'newest';
}

// if (! in_array($filter, ['newest', 'popular', 'all'], true)) {
// 	$filter = 'newest';
// }

$paged = max(1, (int) (get_query_var('paged') ?: get_query_var('page') ?: ($_GET['blog_paged'] ?? 1))); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

$excluded = jhg_blog_excluded_post_ids();
switch ($filter) {
	case 'popular':
		$order_args = [
			'orderby'    => 'meta_value_num',
			'order'      => 'DESC',
			'meta_key'   => 'post_views_count',
			'meta_query' => [
				'relation' => 'OR',
				[
					'key'     => 'post_views_count',
					'compare' => 'EXISTS',
				],
				[
					'key'     => 'post_views_count',
					'compare' => 'NOT EXISTS',
				],
			],
		];
		break;

	case 'all':
	default:
		$order_args = [
			'orderby' => 'date',
			'order'   => 'DESC',
		];
		break;
}

$query_base = [
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'post__not_in'        => $excluded,
	'ignore_sticky_posts' => true,
];

$all_query = new WP_Query(array_merge($query_base, $order_args, [
	'posts_per_page' => 4,
	'paged'          => $paged,
]));

$popular_query = new WP_Query(array_merge($query_base, [
	'posts_per_page' => 7,
	'meta_key'       => 'post_views_count',
	'orderby'        => 'meta_value_num',
	'order'          => 'DESC',
	'post_status'    => 'publish',
	'no_found_rows'  => true,
]));

$base_url = get_permalink();

$filter_link = static function (string $key) use ($base_url): string {
	return esc_url(add_query_arg('filter', $key, $base_url));
};

$socials = function_exists('get_field') ? (get_field('social_links', 'option') ?: []) : [];
$socials = array_values(array_filter($socials, static function ($row) {
	if ('' === jhg_acf_link_url($row['url'] ?? '')) {
		return false;
	}

	$icon = strtolower((string) ($row['icon'] ?? ''));

	return str_contains($icon, 'facebook') || str_contains($icon, 'linkedin');
}));

//$has_newest_posts = $newest_query->have_posts();
?>
<section class="jhg-blog-hub jhg-section">
	<div class="container">
		<header class="jhg-blog-hub-header">
			<?php jhg_blog_dot_heading(__('JHG Blogs', 'jhg-maven'), 'h2', 'jhg-blog-dot-heading jhg-blog-hub-heading'); ?>
			<hr class="jhg-blog-hub-rule">


		</header>

		<?php if ($banner_src) : ?>
			<a class="jhg-blog-hub-banner" href="<?php echo esc_url($banner_href); ?>" <?php echo jhg_acf_link_attrs($featured_link); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
																						?>>
				<img
					class="jhg-blog-hub-banner-img"
					src="<?php echo esc_url($banner_src); ?>"
					alt=""
					loading="lazy"
					decoding="async">
			</a>
		<?php endif; ?>

		<header class="jhg-blog-hub-header">
			<div class="jhg-blog-hub-filters body-xs">
				<span class="jhg-blog-hub-filters-label"><?php esc_html_e('Filter articles by:', 'jhg-maven'); ?></span>
				<nav class="jhg-blog-hub-filters-nav" aria-label="<?php esc_attr_e('Blog filters', 'jhg-maven'); ?>">
					<a
						class="jhg-blog-hub-filter<?php echo 'popular' === $filter ? ' is-active' : ''; ?>"
						href="<?php echo $filter_link('popular'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
								?>"><?php esc_html_e('Popular', 'jhg-maven'); ?></a>
					<a
						class="jhg-blog-hub-filter<?php echo 'all' === $filter ? ' is-active' : ''; ?>"
						href="<?php echo $filter_link('all'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
								?>"><?php esc_html_e('ALL', 'jhg-maven'); ?></a>
				</nav>
			</div>
		</header>
		<div class="row align-items-start jhg-blog-hub-body">
			<div class="col-12 col-lg-8 jhg-blog-hub-feed">
				<?php if ($all_query->have_posts()) : ?>
					<section class="jhg-blog-hub-block jhg-blog-hub-all-section">
						<?php jhg_blog_dot_heading(__('All blogs', 'jhg-maven'), 'h3', 'jhg-blog-dot-heading jhg-blog-hub-block-heading'); ?>

						<div class="row row-cols-1 row-cols-md-2 g-5">
							<?php
							while ($all_query->have_posts()) {
								$all_query->the_post();
							?>
								<div class="col">
									<?php get_template_part('template-parts/components/blog-card', 'large', ['post_id' => get_the_ID()]); ?>
								</div>
							<?php
							}
							wp_reset_postdata();
							?>
						</div>

						<?php if ($all_query->max_num_pages > 1) : ?>
							<?php jhg_blog_hub_pagination($all_query->max_num_pages, $paged, $base_url, $filter); ?>
						<?php endif; ?>
					</section>
				<?php endif; ?>
			</div>

			<aside class="col-12 col-lg-4 jhg-blog-hub-sidebar border-lg-start ps-lg-4 mt-4 mt-lg-0">
				<?php if ($popular_query->have_posts()) : ?>
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
				<?php endif; ?>

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
									<?php echo jhg_acf_link_attrs($social['url'], '_blank'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
									?>
									aria-label="<?php echo esc_attr(jhg_social_icon_label($social['icon'] ?? '')); ?>">
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
			</aside>
		</div>
	</div>
</section>