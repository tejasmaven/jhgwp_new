<?php

/**
 * Single blog post layout.
 */

get_header();

if (have_posts()) :
	while (have_posts()) :
		the_post();
		//get_template_part('template-parts/blog/single', 'hero');
		//	get_template_part('template-parts/blog/single', 'logo-strip');
		get_template_part('template-parts/blog/single', 'lead');
		get_template_part('template-parts/blog/single', 'article');
		get_template_part('template-parts/blog/single', 'featured');
		get_template_part('template-parts/blog/single', 'related');
	endwhile;
endif;

get_footer();
