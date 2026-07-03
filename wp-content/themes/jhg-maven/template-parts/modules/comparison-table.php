<?php

/**
 * Module: Comparison Table
 */

$heading = get_sub_field('heading');
$intro   = get_sub_field('intro');
$rows    = get_sub_field('rows');

if (! $rows) {
	return;
}
?>
<section class="jhg-comparison-table jhg-section">
	<div class="container">
		<div class="jhg-comparison-table-panel">
			<div class="jhg-comparison-table-inner">
				<?php if ($heading) : ?>
					<h2 class="jhg-comparison-table-heading title-md"><?php echo esc_html($heading); ?></h2>
				<?php endif; ?>

				<?php if ($intro) : ?>
					<p class="jhg-comparison-table-intro body-lg"><?php echo esc_html($intro); ?></p>
				<?php endif; ?>

				<div class="table-responsive-lg jhg-comparison-table-scroll" tabindex="0">
					<table class="table table-borderless align-middle mb-0 jhg-comparison-table-table">
						<colgroup>
							<col class="jhg-comparison-table-col-label">
							<col class="jhg-comparison-table-col-jhg">
							<col class="jhg-comparison-table-col-generic">
						</colgroup>
						<thead class="jhg-comparison-table-head">
							<tr>
								<th scope="col" class="text-start body-md"><?php esc_html_e('Comparative list', 'jhg-maven'); ?></th>
								<th scope="col" class="text-start body-md"><?php esc_html_e('JHG Consultant', 'jhg-maven'); ?></th>
								<th scope="col" class="text-start body-md"><?php esc_html_e('Generic HR Consultant', 'jhg-maven'); ?></th>
							</tr>
						</thead>
						<tbody class="jhg-comparison-table-body table-group-divider">
							<?php foreach ($rows as $row) : ?>
								<?php
								$label = $row['label'] ?? '';
								$jhg   = trim((string) ($row['jhg'] ?? ''));
								$trad  = trim((string) ($row['traditional'] ?? ''));
								?>
								<?php if ($label) : ?>
									<tr>
										<th scope="row" class="text-start fw-bold body-lg"><?php echo esc_html($label); ?></th>
										<td class="text-start">
											<?php
											if (function_exists('jhg_comparison_table_value_cell')) {
												jhg_comparison_table_value_cell($jhg, 'jhg');
											}
											?>
										</td>
										<td class="text-start">
											<?php
											if (function_exists('jhg_comparison_table_value_cell')) {
												jhg_comparison_table_value_cell($trad, 'traditional');
											}
											?>
										</td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
