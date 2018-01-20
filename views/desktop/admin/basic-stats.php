<?php

$this->params['breadcrumbs'][] = [
	'label' => 'Basic stats',
	'url' => ['/admin/basic-stats']
];

\app\assets\desktop\admin\AdminsAsset::register($this);

$this->title = 'Todevise / Admin / Basic stats';
?>

<div class="row no-gutter">
	<div class="col-xs-12 no-horizontal-padding">

		<div class="row no-gutter page-title-row">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071">Basic stats</h2>
				</div>
				<div class="col-xs-6 col-height col-middle flex flex-align-center">

				</div>
				<div class="col-xs-4 col-height col-middle">

				</div>
			</div>
		</div>
		<div id="admins_list" class="funiv fc-fff fs1-071">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Item</th>
						<th>Info</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($stats as $name => $info) { ?>
						<tr>
							<td><?=$name?></td>
							<td><?=$info?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>