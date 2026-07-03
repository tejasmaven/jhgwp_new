<?php

/**
 * Module: Legal Document
 */

$heading      = trim((string) get_sub_field('heading'));
$content      = get_sub_field('content');
$intro        = trim((string) get_sub_field('intro'));
$last_updated = trim((string) get_sub_field('last_updated'));

if (! $heading && ! $content) {
	return;
}

$toc_items = [];

if (is_string($content) && preg_match_all('/<h2[^>]*\sid=["\']([^"\']+)["\'][^>]*>(.*?)<\/h2>/is', $content, $matches, PREG_SET_ORDER)) {
	foreach ($matches as $match) {
		$label = trim(wp_strip_all_tags($match[2]));

		if ('' === $label) {
			continue;
		}

		$toc_items[] = [
			'id'    => sanitize_title($match[1]),
			'label' => $label,
		];
	}
}
?>
<section class="jhg-legal-document jhg-section">
	<div class="container">
		<header class="jhg-legal-document-header">
			<p class="jhg-legal-document-eyebrow body-sm"><?php esc_html_e('Legal information', 'jhg-maven'); ?></p>

			<?php if ($heading) : ?>
				<h1 class="jhg-legal-document-title title-3xl"><?php echo esc_html($heading); ?></h1>
			<?php endif; ?>

			<?php if ($last_updated) : ?>
				<p class="jhg-legal-document-meta body-sm">
					<?php
					printf(
						/* translators: %s: last updated date */
						esc_html__('Last updated: %s', 'jhg-maven'),
						esc_html($last_updated)
					);
					?>
				</p>
			<?php endif; ?>

			<?php if ($intro) : ?>
				<p class="jhg-legal-document-intro body-lg"><?php echo esc_html($intro); ?></p>
			<?php endif; ?>

			<hr class="jhg-legal-document-rule" aria-hidden="true">
		</header>

		<div class="jhg-legal-document-layout">
			<?php if ($toc_items) : ?>
				<aside class="jhg-legal-document-aside">
					<nav class="jhg-legal-document-toc" aria-label="<?php esc_attr_e('On this page', 'jhg-maven'); ?>">
						<p class="jhg-legal-document-toc-heading body-sm"><?php esc_html_e('On this page', 'jhg-maven'); ?></p>
						<ol class="jhg-legal-document-toc-list">
							<?php foreach ($toc_items as $item) : ?>
								<li>
									<a class="jhg-legal-document-toc-link body-sm" href="#<?php echo esc_attr($item['id']); ?>">
										<?php echo esc_html($item['label']); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ol>
					</nav>
				</aside>
			<?php endif; ?>

			<?php if ($content) : ?>
				<div class="jhg-legal-document-body body-md">
					<?php echo wp_kses_post($content); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
