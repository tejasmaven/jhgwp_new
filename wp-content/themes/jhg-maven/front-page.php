<?php

/**
 * Front page — renders the modular page builder.
 *
 * There are no fixed per-page templates; every page (home, about, contact, …)
 * is composed from the "Page Sections" flexible-content field.
 */

get_header();

while (have_posts()) :
	the_post();
	jhg_render_modules();
endwhile;

get_footer();
