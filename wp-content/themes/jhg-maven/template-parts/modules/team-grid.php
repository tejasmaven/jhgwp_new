<?php

/**
 * Module: Team Grid
 */


$show_team_memebers = get_sub_field('show_team_memebers');

if ($show_team_memebers == true) {
	$team_query_args = [
		'post_type' => 'jhg_team',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		//'orderby' => 'menu_order',
		'orderby' => 'title',
		'order' => 'ASC',
	];
	$team_memebers = new WP_Query($team_query_args);

	if ($team_memebers->have_posts()) {
?>
		<section class="jhg-team-grid jhg-section">
			<div class="container">
				<div class="jhg-team-grid-list">
					<?php
					while ($team_memebers->have_posts()) {
						$team_memebers->the_post();
						$team_id = get_the_ID();

						$name = get_the_title($team_id);
						$photo = get_field('photo', $team_id);
						$role = get_field('role', $team_id);
						$email = get_field('email', $team_id);
						$linkedin_url = get_field('linkedin', $team_id);

						$linkedin = jhg_acf_link_url($linkedin_url ?? '');
						$linkedin_link = $linkedin_url ?? null;

						if (! $name) {
							continue;
						}

						$mailto = '';
						if ($email) {
							$mailto = (0 === strpos($email, 'mailto:')) ? $email : 'mailto:' . $email;
						}
					?>
						<article class="jhg-team-card">
							<?php if ($photo && ! empty($photo['url'])) : ?>
								<div class="jhg-team-card-photo">
									<img
										src="<?php echo esc_url($photo['url']); ?>"
										alt="<?php echo esc_attr($photo['alt'] ?: $name); ?>"
										loading="lazy">
								</div>
							<?php endif; ?>

							<h2 class="jhg-team-card-name title-xl"><?php echo esc_html($name); ?></h2>

							<?php if ($role) : ?>
								<p class="jhg-team-card-role body-md"><?php echo esc_html($role); ?></p>
							<?php endif; ?>

							<?php if ($mailto || $linkedin) : ?>
								<span class="jhg-team-card-divider" aria-hidden="true"></span>

								<div class="jhg-team-card-social">
									<?php if ($mailto) : ?>
										<a
											class="jhg-team-card-social-link"
											href="<?php echo esc_url($mailto); ?>"
											aria-label="<?php echo esc_attr(sprintf(__('Email %s', 'jhg-maven'), $name)); ?>">
											<i class="fa-regular fa-envelope" aria-hidden="true"></i>
										</a>
									<?php endif; ?>

									<?php if ($mailto && $linkedin) : ?>
										<span class="jhg-team-card-social-divider" aria-hidden="true"></span>
									<?php endif; ?>

									<?php if ($linkedin) : ?>
										<a
											class="jhg-team-card-social-link"
											href="<?php echo esc_url($linkedin); ?>"
											<?php echo jhg_acf_link_attrs($linkedin_link, '_blank'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
											?>
											aria-label="<?php echo esc_attr(sprintf(__('LinkedIn profile of %s', 'jhg-maven'), $name)); ?>">
											<i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
										</a>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</article>
					<?php }	?>
				</div>
			</div>
		</section>
<?php
	}
}
