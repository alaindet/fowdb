<?php
	global $filters;
	global $cards; // Results
	global $s; // Search model instance
	$spoilerCodes = \App\Legacy\Helpers::get('spoiler.codes');
	$spoilerCodes = !empty($spoilerCodes) ? $spoilerCodes : [];
?>
<div class="col-xs-12" id="search-results">
	<a name="top"></a>
	<div class="panel panel-default">
		
		<!-- Results header -->
		<div class="panel-heading">
			<h4>
				<i class="fa fa-th-large"></i>&nbsp;Results
				<small>
					<?php // Decide on what counts to show
						$cardsCount = $s->getCardsCount();
						$partialCount = $cardsCount > DB_RESULTS_LIMIT ? DB_RESULTS_LIMIT : $cardsCount;
					?>
					(Showing <span id="cards-counter"><?=$partialCount?></span> out of <strong><?=$cardsCount?></strong>)
				</small>
			</h4>
		</div>
		
		<!-- Results body -->
		<div class="panel-body" id="cards-container"><!--
			<?php if(!empty($cards)): ?>
				<?php foreach ($cards as &$card): ?>
					<?php
						$spoiled = in_array($card['setcode'], $spoilerCodes) ? ' fdb-card-spoiled' : '';
						$link = "/?p=card&code=".str_replace(" ", "+", $card['code']);
					?>
					--><!-- Card --><!--
					--><div class="fdb-card<?=$spoiled?>"><!--
						--><a href="<?=$link?>" target="_self"><!--
							--><img src="<?=$card['thumb_path']?>" data-code="<?=$card['code']?>" data-id="<?=$card['id']?>" data-set="<?=$card['setcode']?>" alt="<?=$card['cardname']?>"><!--
						--></a><!--
					--></div><!-- /Card --><!--
				<?php endforeach; ?>
				<?php if ($s->isPagination): ?>
					--><div class="fdb-card fdb-card-3 fdb-card-load pointer">
						<form method="post" action="?p=nojs-search" class="form-inline text-center" id="loadCards">
							<input type="hidden" name="token" value="<?=$_SESSION['token']?>">
							<input type="hidden" name="page" value="<?=isset($filters['page'])?$filters['page']:"1"?>">
							<button type="submit" class="fdb-load-btn">
								<span class="fdb-loading-icon">
									<i class="fa fa-2x fa-refresh"></i>
									<br><strong>Load</strong>
								</span>
								<img src="images/in_pages/search/more.jpg">
							</button>
						</form>
					</div><!--
				<?php endif; ?>
				--><hr>
				<!-- Top anchor -->
				<div class="text-center">
					<a href="#top" class="local text-center">Top</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
