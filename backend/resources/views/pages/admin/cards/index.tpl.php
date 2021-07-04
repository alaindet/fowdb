<div class="page-header">
  <h1>Cards</h1>
  <?=component('breadcrumb', [
    'Admin' => url('profile'),
    'Cards' => '#'
  ])?>
</div>

<!-- Create a new ruling -->
<a
  href="<?=url('cards/create')?>"
  class="btn btn-lg fd-btn-default fd-btn-success-hover"
>
  <i class="fa fa-plus"></i>
  Create a new card
</a>
<hr>

<div class="fd-box font-110">
  If you need to <strong>update</strong> or <strong>delete</strong> a card, just use the public <a href="<?=url('/cards/search')?>">search page</a>, reach the card's page, then click on <span class="btn btn-xs btn-warning">Edit card</span> or <span class="btn btn-xs btn-danger">Delete card</span> to go to that action's page.
</div>
