<?php

/**
 * Blog single: hero
 */

$post_id  = get_the_ID();
$thumb_id = get_post_thumbnail_id($post_id);
$title    = get_the_title();
$hero_url = '';

if ($thumb_id) {
	$hero_url = (string) wp_get_attachment_image_url($thumb_id, 'full');
}

if ('' === $hero_url) {
	$hero_url = jhg_blog_featured_banner_url() ?: jhg_blog_landing_hero_url();
}
?>
<section class="jhg-blog-single-hero"<?php echo $hero_url ? ' style="background-image:url(' . esc_url($hero_url) . ');"' : ''; ?>>
	<?php if ($hero_url) : ?>
		<div class="jhg-blog-single-hero-media" aria-hidden="true">
			<img
				class="jhg-blog-single-hero-img"
				src="<?php echo esc_url($hero_url); ?>"
				alt=""
				loading="eager"
				decoding="async"
				fetchpriority="high"
			>
		</div>
	<?php endif; ?>

	<div class="jhg-blog-single-hero-shade" aria-hidden="true"></div>

	<div class="container jhg-blog-single-hero-caption">
		<h1 class="jhg-blog-single-hero-title title-display"><?php echo esc_html($title); ?></h1>
	</div>
</section>
