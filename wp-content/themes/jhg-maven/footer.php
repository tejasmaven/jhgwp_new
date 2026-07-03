<?php

/**
 * Footer: closes <main>, renders the global footer from Theme Settings.
 */

$jhg_about     = jhg_theme_option('footer_about', '');
$jhg_address   = jhg_theme_option('footer_address', '');
$jhg_phone     = jhg_theme_option('footer_phone', '');
$jhg_email     = jhg_theme_option('footer_email', '');
$jhg_socials   = function_exists('get_field') ? (get_field('social_links', 'option') ?: []) : [];
$jhg_copyright = jhg_theme_option('copyright', '');


$jhg_company_links = jhg_get_footer_company_links();

$jhg_footer_bottom_links = jhg_footer_bottom_links();

?>
</main><!-- #jhg-main -->

<footer class="jhg-footer">
	<div class="container">
		<div class="jhg-footer-inner">
			<div class="jhg-footer-top">
				<div class="jhg-footer-about">
					<h2 class="jhg-footer-heading"><?php esc_html_e('About', 'jhg-maven'); ?></h2>

					<?php if ($jhg_about) : ?>
						<p class="jhg-footer-about-text"><?php echo esc_html($jhg_about); ?></p>
					<?php endif; ?>

					<?php if ($jhg_address || $jhg_phone || $jhg_email) : ?>
						<p class="jhg-footer-contact">
							<?php if ($jhg_address) : ?>
								<span class="jhg-footer-contact-label"><?php esc_html_e('A:', 'jhg-maven'); ?></span>
								<?php echo esc_html($jhg_address); ?><br>
							<?php endif; ?>

							<?php if ($jhg_phone) : ?>
								<span class="jhg-footer-contact-label"><?php esc_html_e('P:', 'jhg-maven'); ?></span>
								<a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $jhg_phone)); ?>"><?php echo esc_html($jhg_phone); ?></a><br>
							<?php endif; ?>

							<?php if ($jhg_email) : ?>
								<span class="jhg-footer-contact-label"><?php esc_html_e('E:', 'jhg-maven'); ?></span>
								<a href="mailto:<?php echo esc_attr($jhg_email); ?>"><?php echo esc_html($jhg_email); ?></a>
							<?php endif; ?>
						</p>
					<?php endif; ?>
				</div>

				<div class="jhg-footer-top-spacer" aria-hidden="true"></div>

				<div class="jhg-footer-nav-col">
					<h2 class="jhg-footer-heading"><?php esc_html_e('Company', 'jhg-maven'); ?></h2>
					<?php if (! empty($jhg_company_links)) : ?>
						<ul class="jhg-footer-links list-unstyled mb-0">
							<?php foreach ($jhg_company_links as $link) : ?>
								<li class="<?php echo ! empty($link['current']) ? 'is-active' : ''; ?>">
									<a
										href="<?php echo esc_url($link['url']); ?>"
										<?php echo ! empty($link['current']) ? ' aria-current="page"' : ''; ?>><?php echo esc_html($link['label']); ?></a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>

				<div class="jhg-footer-top-spacer" aria-hidden="true"></div>

				<div class="jhg-footer-social-col">
					<h2 class="jhg-footer-heading"><?php esc_html_e('Follow us', 'jhg-maven'); ?></h2>
					<?php if (! empty($jhg_socials)) : ?>

						<div class="jhg-footer-social">
							<?php foreach ($jhg_socials as $social) : ?>

								<?php if (jhg_acf_link_url($social['url'] ?? '')) : ?>
									<a
										class="jhg-footer-social-link"
										href="<?php echo esc_url(jhg_acf_link_url($social['url'])); ?>"
										<?php echo jhg_acf_link_attrs($social['url'], '_blank'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
										?>
										aria-label="<?php echo esc_attr(jhg_social_icon_label($social['icon'] ?? '')); ?>">
										<i class="<?php echo esc_attr(jhg_social_icon_classes($social['icon'] ?? '')); ?>" aria-hidden="true"></i>
									</a>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<hr class="jhg-footer-divider">

			<div class="jhg-footer-bar">
				<?php if ($jhg_copyright) : ?>
					<p class="jhg-footer-copyright"><?php echo esc_html($jhg_copyright) . ", " . date('Y') . '.'; ?></p>
				<?php endif; ?>
				<div class="jhg-footer-bar-spacer" aria-hidden="true"></div>

				<?php if (! empty($jhg_footer_bottom_links)) : ?>

					<?php foreach ($jhg_footer_bottom_links as $footer_link) : ?>
						<a class="jhg-footer-legal-link" href="<?php echo $footer_link['url']; ?>">
							<?php echo esc_html($footer_link['label']); ?>
						</a>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>

</html>