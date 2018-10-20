<?php
	require __DIR__ . '/races.sql.php';
  $url  = url('/', ['do' => 'search', 'race' => '_RACE_']);
?>

<div class="page-header">
	<h1>Races and Traits</h1>
</div>

<div class="row">

	<!-- Races -->
	<div class="col-xs-6">
		<table class="table table-striped table-condensed races-table">
			<thead>
				<tr>
					<th><h4><strong>Races</strong> (<?=count($races)?>)</h4></th>
				</tr>
			</thead>
			
			<tbody>
				<?php foreach ($races as &$race): ?>
					<tr>
						<td>
              <a
                href="<?=url('/', [
                  'do' => 'search',
                  'race' => urlencode(strtolower($race))
                ])?>"
                target="_blank"
              >
								<?=$race?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	
	<!-- Traits -->
	<div class="col-xs-6">
		<table class="table table-striped table-condensed traits-table">
			<thead>
				<tr>
					<th><h4><strong>Traits</strong> (<?=count($traits)?>)</h4></td>
				</tr>
			</thead>
			
			<tbody>
				<?php foreach ($traits as &$trait): ?>
					<tr>
						<td>
							<a
                href="<?=url('/', [
                  'do' => 'search',
                  'race' => urlencode(strtolower($trait))
                ])?>"
                target="_blank"
              >
								<?=$trait?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
  </div>
  
</div>
