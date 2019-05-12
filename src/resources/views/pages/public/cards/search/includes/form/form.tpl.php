<?php

// VARIABLES
// $areResults
// $state

// INPUTS
// q
// atk
// atk-operator
// attribute[]
// attribute-multi
// attribute-single
// attribute-selected
// back-side[]
// def
// def-operator
// divinity[]
// partial-match
// exclude[]
// format[]
// illust
// in-fields[]
// q
// quickcast
// race
// rarity[]
// set[]?
// sort
// sort-dir
// cost[]
// cost-x
// type[]
// type-selected

?>
<form
  method="GET"
  action="<?=url('cards')?>"
  class="form"
  id="the-form"
>
  <div class="row">

    <!-- Search bar -->
    <div class="col-xs-12 mb-50">
      <?=fd_include_view('pages/public/cards/search/includes/form/searchbar', [
        'areResults' => $areResults,
        'state' => $state,
      ])?>
    </div>

    <!-- Filters -->
    <div class="col-xs-12">
      <?=fd_include_view('pages/public/cards/search/includes/form/filters', [
        'areResults' => $areResults,
        'state' => $state,
      ])?>
    </div>

  </div>
</form>
