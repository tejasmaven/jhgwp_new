<?php

/**
 * Single service template — built with the modular page builder.
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
