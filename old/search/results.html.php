<?php
// VARIABLES
// $cards
// $filters
// $search
// $thereWereResults

$spoilerIds = lookup('spoilers.ids');
?>
<div class="col-xs-12" id="search-results">
	<a name="top"></a>
	<div class="panel panel-default">
		
		<!-- Results header -->
		<div class="panel-heading">
			<h3>
        <i class="fa fa-th-large"></i>
        Results
				<small>
					<?php // Decide on what counts to show
						$cardsCount = $search->getCardsCount();
            $limit = config('db.results.limit');
            $partialCount =  $cardsCount > $limit ? $limit : $cardsCount;
					?>
					(Showing <span id="cards-counter"><?=$partialCount?></span> out of <strong><?=$cardsCount?></strong>)
				</small>
			</h3>
		</div>
		
		<!-- Results body -->
		<div class="panel-body" id="cards-container"><!--
			<?php if(!empty($cards)): ?>
				<?php foreach ($cards as &$card): ?>
					<?php
            $spoiled = in_array($card['sets_id'], $spoilerIds)
              ? ' fdb-card-spoiled'
              : '';
						$link = url_old('card', [ 'code' => urlencode($card['code']) ]);
					?>
					--><!-- Card --><!--
					--><div class="fdb-card<?=$spoiled?>"><!--
						--><a href="<?=$link?>" target="_self"><!--
							--><img src="<?=$card['thumb_path']?>" data-id="<?=$card['id']?>" alt="<?=$card['name']?>"><!--
						--></a><!--
					--></div><!-- /Card --><!--
				<?php endforeach; ?>
				<?php if ($search->isPagination): ?>
					--><div class="fdb-card fdb-card-3 fdb-card-load pointer">
						<form
							method="post"
							action=""
							class="form-inline text-center"
							id="loadCards"
						>
              <?=csrf_token()?>
							<input type="hidden" name="page" value="<?=isset($filters['page'])?$filters['page']:"1"?>">
							<button type="submit" class="fdb-load-btn">
								<span class="fdb-loading-icon">
									<i class="fa fa-2x fa-refresh"></i>
									<br><strong>Load</strong>
								</span>
								<img src="images/in-pages/search/more.jpg">
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
