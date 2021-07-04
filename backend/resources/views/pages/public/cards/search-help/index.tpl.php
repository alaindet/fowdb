<div class="page-header">
  <h1>Cards search help</h1>
  <?=component('breadcrumb', [
    '&larr; Cards search' => url('/'),
    'Help' => '#'
  ])?>
</div>

<h2>
  <a href="#syntax" name="syntax" class="link-as-text link-hash">
    Syntax
  </a>
</h2>

<?=include_view('pages/public/cards/search-help/syntax-table')?>

<h2>
  <a href="#tips-tricks" name="tips-tricks" class="link-as-text link-hash">
    Tips and tricks
  </a>
</h2>

<ul class="fd-list --spaced">
  <li>
    <span class="btn btn-xs fd-btn-default active">Exact Match</span> is clicked by default and forces all card results to match <strong>all</strong> search terms. Unclick this button to get card results matching <strong>at least</strong> one of the search terms.
  </li>
  <li>
    The search query you type gets split into individual search terms by whitespace by default. To <strong>preserve whitespace</strong> in a single search term (ex.: you desperately need to match something like "destroy target resonator"), <strong>use backticks</strong>. <em>Ex.:</em> <code>`destroy target resonator` other terms</code> gets processed as 3 search terms: "destroy target resonator", "other" and "terms".
  </li>
  <li>
    The button <span class="btn btn-xs fd-btn-default">Only Multi-Attribute</span> exclude all single attribute cards from the results.
  </li>
  <li>
    The button <span class="btn btn-xs fd-btn-default">Must contain just selected</span> will match only cards with attributes specified on the Attributes search filters. Best paired with <span class="btn btn-xs fd-btn-default">Only Multi-Attribute</span> to match only multi-attribute cards containing only selected attributes. <em>Ex.: </em> You want fire/wind cards but you definetely don't want to match water/wind cards also.
  </li>
</ul>
