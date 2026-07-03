<?php

/**
 * Module: Access Table
 */

$heading = trim((string) get_sub_field('heading'));
$intro   = trim((string) get_sub_field('intro'));
$rows    = get_sub_field('rows');

if (! is_array($rows) || ! $rows) {
	return;
}
?>
<section class="jhg-access-table jhg-section">
	<div class="container">
		<div class="jhg-access-table-panel">
			<div class="jhg-access-table-inner">
				<?php if ($heading) : ?>
					<h2 class="jhg-access-table-heading title-xl"><?php echo esc_html($heading); ?></h2>
				<?php endif; ?>

				<?php if ($intro) : ?>
					<p class="jhg-access-table-intro body-lg"><?php echo esc_html($intro); ?></p>
				<?php endif; ?>

				<div class="table-responsive-lg jhg-access-table-scroll" tabindex="0">
					<table class="table table-borderless align-middle mb-0 jhg-access-table-table">
						<colgroup>
							<col class="jhg-access-table-col-label">
							<col class="jhg-access-table-col-public">
							<col class="jhg-access-table-col-member">
						</colgroup>
						<thead class="jhg-access-table-head">
							<tr>
								<th scope="col" class="text-start title-lg"><?php esc_html_e('Resource Type', 'jhg-maven'); ?></th>
								<th scope="col" class="text-start title-lg"><?php esc_html_e('Public', 'jhg-maven'); ?></th>
								<th scope="col" class="text-start title-lg"><?php esc_html_e('Member Only', 'jhg-maven'); ?></th>
							</tr>
						</thead>
						<tbody class="jhg-access-table-body table-group-divider">
							<?php foreach ($rows as $row) : ?>
								<?php
								$label  = trim((string) ($row['label'] ?? ''));
								$public = trim((string) ($row['public_access'] ?? ''));
								$member = trim((string) ($row['member_access'] ?? ''));

								if ('' === $label) {
									continue;
								}
								?>
								<tr>
									<th scope="row" class="text-start body-lg"><?php echo esc_html($label); ?></th>
									<td class="text-start">
										<?php
										if (function_exists('jhg_access_table_icon_cell')) {
											jhg_access_table_icon_cell($public);
										}
										?>
									</td>
									<td class="text-start">
										<?php
										if (function_exists('jhg_access_table_icon_cell')) {
											jhg_access_table_icon_cell($member);
										}
										?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
