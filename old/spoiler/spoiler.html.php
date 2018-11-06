<div class="col-xs-12" id="search-results">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>
				<i class="fa fa-th-large"></i>
				Spoiler
				<button
					type="button"
					class="btn btn-link js-hider js-panel-toggle"
					data-target="#hide-options"
					data-open-icon="fa-times"
					data-closed-icon="fa-plus"
					id="js-panel-toggle-options">
		        <i class="fa fa-plus"></i>&nbsp;Options
		    </button>
		   </h3>
		</div>
		<div class="panel-body cards-container"><!--
			<?php foreach ($cards as &$set) : ?>
				--><div
					id="<?=$set['code']?>"
					class="spoiler"
					data-setcode="<?=$set['code']?>"
					data-setcount="<?=$set['count']?>"
				>
					<!-- Header -->
					<div class="spoiler-header text-center">
						
						<h3
							class="js-hider pointer inline"
							data-target="#hide-spoiler-<?=$set['code']?>"
						>
							<i class="fa fa-chevron-down"></i>
							<?="{$set['name']} ({$set['spoiled']} / {$set['count']})"?>
						</h3>
						
						<a href="#top" class="btn btn-link">
							Top
						</a>

						<a
							class="btn btn-link"
							href="#<?=$set['code']?>"
							name="<?=$set['code']?>"
						>
							Share
						</a>
            
					</div>
					<br>
					<!-- Body -->
					<div class="spoiler-body" id="hide-spoiler-<?=$set['code']?>"><!--
						<?php if (!empty($set['cards'])): ?>
							<?php foreach ($set['cards'] as &$card): ?>
									--><div class="fdb-card"><!--
										--><a
											href="<?=url_old('card', ['code' => urlencode($card['code'])])?>"
											target="_self"
										><!--
											--><img
                        src="<?=$card['thumb_path']?>"
                        alt="<?=$card['name']?>"
                      ><!--
										--></a><!--
									--></div><!--
							<?php endforeach; ?>
						<?php endif; ?>
					--></div><!--
				--></div><!--
			<?php endforeach; ?>-->
			<?=component('top-anchor')?>
		</div>
	</div>
</div>
