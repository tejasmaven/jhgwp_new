<?php

/**
 * Blog single: lead
 */

$intro = jhg_blog_landing_intro_html();

if ('' === $intro) {
	return;
}
?>
<section class="jhg-blog-single-lead jhg-section">
	<div class="container">
		<div class="jhg-blog-single-lead-body body-xl">
			<?php echo wp_kses_post($intro); ?>
		</div>
	</div>
</section>
