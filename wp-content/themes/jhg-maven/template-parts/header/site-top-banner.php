<?php
$bg_type = (string) (get_field('hero_banner_background_type') ?: 'image');
$bg_image = get_field('hero_banner_background_image');
$bg_video = get_field('hero_banner_background_video');
$title = get_field('hero_banner_title');
$subtitle = get_field('hero_banner_subtitle');
$btn_text = get_field('hero_banner_button_text');
$btn_url = get_field('hero_banner_button_url');
$btn_url_target = get_field('hero_banner_button_target');

$image_url = ($bg_image && ! empty($bg_image['url'])) ? (string) $bg_image['url'] : '';
$image_alt = ($bg_image && ! empty($bg_image['alt'])) ? (string) $bg_image['alt'] : '';
$video_url = ($bg_video && ! empty($bg_video['url'])) ? (string) $bg_video['url'] : '';
$video_mime = ($bg_video && ! empty($bg_video['mime_type'])) ? (string) $bg_video['mime_type'] : 'video/mp4';

$use_video = ('video' === $bg_type && '' !== $video_url);
$use_image = (! $use_video && '' !== $image_url);
$has_media = $use_video || $use_image;

$has_content = ($title || $subtitle || $btn_url);
$media_only = $has_media && ! $has_content;

$hero_classes = ['jhg-hero'];

if ($media_only) {
	$hero_classes[] = 'jhg-hero-image';
}

if ($use_video) {
	$hero_classes[] = 'jhg-hero-has-video';

	if ('' !== $image_url) {
		$hero_classes[] = 'jhg-hero-has-poster';
	}
}

$section_style = '';

if ($use_image && $has_content) {
	$section_style = ' style="background-image:url(' . esc_url($image_url) . ');"';
} elseif ($use_video && '' !== $image_url) {
	$section_style = ' style="background-image:url(' . esc_url($image_url) . ');"';
}
if ($has_media) :
?>
	<section class="<?php echo esc_attr(implode(' ', $hero_classes)); ?>" <?php echo $section_style; ?>>
		<?php if ($use_video) : ?>
			<div class="jhg-hero-media" aria-hidden="true">
				<video
					class="jhg-hero-video"
					autoplay
					muted
					loop
					playsinline
					preload="metadata"
					<?php echo '' !== $image_url ? ' poster="' . esc_url($image_url) . '"' : ''; ?>>
					<source src="<?php echo esc_url($video_url); ?>" type="<?php echo esc_attr($video_mime); ?>">
				</video>
			</div>
		<?php elseif ($media_only && $use_image) : ?>
			<img class="jhg-hero-img" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">
		<?php endif; ?>

		<?php if ($has_content) : ?>
			<div class="jhg-hero-overlay"></div>
			<div class="container jhg-hero-inner">
				<div class="jhg-hero-content">
					<?php if ($title) : ?>
						<h1 class="jhg-hero-title title-display"><?php echo esc_html($title); ?></h1>
					<?php endif; ?>

					<?php if ($subtitle) : ?>
						<p class="jhg-hero-subtitle body-lg"><?php echo esc_html($subtitle); ?></p>
					<?php endif; ?>

					<?php if ($btn_url) : ?>
						<?php
						jhg_render_button($btn_text, [
							'url'     => $btn_url ?: '#',
							'variant' => 'red',
							'class'   => 'jhg-hero-btn body-md',
						]);
						?>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</section>
<?php endif; ?>