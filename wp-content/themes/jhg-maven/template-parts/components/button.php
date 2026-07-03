<?php

/**
 * Shared button component.
 */

$args = isset($args) && is_array($args) ? $args : [];

$text    = trim((string) ($args['text'] ?? ''));
$link    = $args['url'] ?? $args['link'] ?? '#';
$url     = function_exists('jhg_acf_link_url') ? jhg_acf_link_url($link, '#') : (string) $link;
$target  = trim((string) ($args['target'] ?? ''));

if ('' === $target && function_exists('jhg_acf_link_target')) {
	$target = jhg_acf_link_target($link);
}

$variant = sanitize_html_class((string) ($args['variant'] ?? 'red'));
$icon    = ! empty($args['icon']);
$extra   = trim((string) ($args['class'] ?? ''));
$link_attrs = function_exists('jhg_acf_link_attrs') ? jhg_acf_link_attrs($link, $target) : '';

if ('' === $text) {
	return;
}

$classes = ['jhg-btn', 'jhg-btn-' . $variant];

if ($icon) {
	$classes[] = 'jhg-btn-with-icon';
}

if ('' !== $extra) {
	$classes[] = $extra;
}
?>
<a class="<?php echo esc_attr(implode(' ', $classes)); ?>" href="<?php echo esc_url($url ?: '#'); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ($icon) : ?>
		<span class="jhg-btn-label"><?php echo esc_html($text); ?></span>
		<span class="jhg-btn-icon" aria-hidden="true">
			<i class="fa-solid fa-arrow-right"></i>
		</span>
	<?php else : ?>
		<?php echo esc_html($text); ?>
	<?php endif; ?>
</a>
