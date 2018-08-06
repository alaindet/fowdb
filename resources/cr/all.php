<div class="page-header">
	<h1>Comprehensive Rules</h1>
</div>
<ul class="fdb-list">
  <?php foreach ($crs as &$cr): ?>
  	<li class="separate">
      <div>
    		<a href="/?p=resources/cr&v=<?=$cr['version']?>" target="_self">
    			<strong>Version:</strong> <?=$cr['version']?> //
          <strong>Legal from:</strong> <?=$cr['date_validity']?>
          <?=$cr['is_default']?"// <span class='text-danger'><strong>CURRENT</strong></span>":""?>
    			<span class="glyphicon glyphicon-new-window"></span>
    		</a>
      </div>
  	</li>
  <?php endforeach; ?>
</ul>
