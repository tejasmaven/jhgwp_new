<?php

/**
 * Common page template — every page is built from the modular page builder.
 *
 * If a page has no modules yet, it falls back to the standard editor content.
 */

get_header();

while (have_posts()) :
	the_post();

	if (function_exists('have_rows') && have_rows('page_modules')) {
		jhg_render_modules();
	} else {
		echo '<div class="container jhg-section">';
		the_content();
		echo '</div>';
	}
endwhile;

get_footer();
