<?php
	// Get races and traits from database
	include 'races-traits.sql.php';

	// Define base url for searching
	$url = "http://{$_SERVER['SERVER_NAME']}/?do=search&race="
?>

<!-- Header -->
<div class="page-header">
	<h1>List of Races and Traits</h1>
</div>

<div class="row">
	<!-- RACES -->
	<div class="col-xs-6">
		<table class="table table-striped table-condensed races-table">
			<thead>
				<tr>
					<th><h4><strong>Races</strong> (<?php echo count($races); ?>)</h4></th>
				</tr>
			</thead>
			
			<tbody>
				<?php foreach ($races as $race): ?>
					<tr>
						<td>
							<a href="<?=$url.urlencode(strtolower($race))?>" target="_blank">
								<?=$race?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div><!-- /RACES -->
	
	<!-- TRAITS -->
	<div class="col-xs-6">
		<table class="table table-striped table-condensed traits-table">
			<thead>
				<tr>
					<th><h4><strong>Traits</strong> (<?php echo count($traits); ?>)</h4></td>
				</tr>
			</thead>
			
			<tbody>
				<?php foreach ($traits as $trait): ?>
					<tr>
						<td>
							<a href="<?=$url.urlencode(strtolower($trait))?>" target="_blank">
								<?=$trait?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div><!-- /TRAITS -->
</div>
