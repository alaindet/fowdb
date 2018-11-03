<div class="page-header">
	<h1>Comprehensive Rules</h1>
</div>
<ul class="fdb-list">
  <?php foreach ($crs as &$cr): ?>
  	<li class="separate">
      <div>
    		<a
					href="<?=url_old('resources/cr', ['v' => $cr['version']])?>"
					target="_self"
				>
    			<strong>Version:</strong> <?=$cr['version']?> //
          <strong>Legal from:</strong> <?=$cr['date_validity']?>
					<?php if ($cr['is_default']): ?>
						<span class="text-danger"><strong>Current</strong></span>
					<?php endif; ?>
    		</a>
      </div>
  	</li>
  <?php endforeach; ?>
</ul>
