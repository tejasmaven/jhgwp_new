<?php

/**
 * Module: Gradient Checklist
 *
 * Each item renders as an inline icon + label row so the check always
 * aligns with its text when labels wrap.
 */

$heading       = trim((string) get_sub_field('heading'));
$column_layout = (string) get_sub_field('column_layout');

if ('' === $column_layout) {
	$column_layout = 'two_column';
}

$left_items  = [];
$right_items = [];
$single_items = [];

if ('single_column' === $column_layout) {
	$single_items = jhg_checklist_repeater_items(get_sub_field('checklist'));
} else {
	$columns     = jhg_gradient_checklist_resolve_columns(
		get_sub_field('checklist_left'),
		get_sub_field('checklist_right')
	);
	$left_items  = $columns['left'];
	$right_items = $columns['right'];
}

$has_items = $single_items || $left_items || $right_items;

if ('' === $heading && ! $has_items) {
	return;
}

$render_column = static function (array $items): void {
	if (! $items) {
		return;
	}
	?>
	<ul class="jhg-gradient-checklist-list">
		<?php foreach ($items as $item) : ?>
			<li>
				<span class="jhg-gradient-checklist-icon" aria-hidden="true">
					<i class="fa-solid fa-check"></i>
				</span>
				<span class="jhg-gradient-checklist-label body-lg"><?php echo esc_html($item); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
};

$columns_class = 'single_column' === $column_layout
	? 'jhg-gradient-checklist-columns jhg-gradient-checklist-columns-single'
	: 'jhg-gradient-checklist-columns';
?>
<section class="jhg-gradient-checklist jhg-section">
	<div class="container">
		<div class="jhg-gradient-checklist-panel">
			<img
				class="jhg-gradient-checklist-corner jhg-gradient-checklist-corner-top"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-top.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28"
			/>
			<img
				class="jhg-gradient-checklist-corner jhg-gradient-checklist-corner-bottom"
				src="<?php echo esc_url(get_theme_file_uri('/assets/images/card-bottom.svg')); ?>"
				alt=""
				aria-hidden="true"
				loading="lazy"
				decoding="async"
				width="114"
				height="28"
			/>
			<div class="jhg-gradient-checklist-frame">
				<div class="jhg-gradient-checklist-inner">
					<?php if ($heading) : ?>
						<h2 class="jhg-gradient-checklist-heading title-3xl"><?php echo esc_html($heading); ?></h2>
					<?php endif; ?>

					<?php if ($has_items) : ?>
						<div class="<?php echo esc_attr($columns_class); ?>">
							<?php if ('single_column' === $column_layout) : ?>
								<div class="jhg-gradient-checklist-column">
									<?php $render_column($single_items); ?>
								</div>
							<?php else : ?>
								<div class="jhg-gradient-checklist-column">
									<?php $render_column($left_items); ?>
								</div>
								<div class="jhg-gradient-checklist-column">
									<?php $render_column($right_items); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
