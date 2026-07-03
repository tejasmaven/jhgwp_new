<?php

/**
 * Module: Section Title
 */

$heading = get_sub_field('heading');
$style   = get_sub_field('style') ?: 'default';

if (! $heading) {
	return;
}

$classes = 'jhg-section-title jhg-section';

$heading_class = 'jhg-section-title-heading';

if ('large' === $style) {
	$classes .= ' jhg-section-title-large';
	$heading_class .= ' title-2xl';
} else {
	$heading_class .= ' title-3xl';
}
?>
<section class="<?php echo esc_attr($classes); ?>">
	<div class="container">
		<h2 class="<?php echo esc_attr($heading_class); ?>"><?php echo esc_html($heading); ?></h2>
	</div>
</section>
