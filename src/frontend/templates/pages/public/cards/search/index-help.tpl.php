<div class="page-header">
  <h1>Cards search help</h1>
  <?=fd_component('breadcrumb', [
    '&larr; Cards search' => fd_url('/'),
    'Help' => '#'
  ])?>
</div>

<h2>
  <a href="#syntax" name="syntax" class="link-as-text link-hash">
    Syntax
  </a>
</h2>

<!-- Syntax table -->
<?=fd_include_template('pages/public/cards/search/includes/help/syntax-table')?>

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
    The button <span class="btn btn-xs fd-btn-default">Only Multi-Attribute</span> excludes all single attribute cards from the results.
  </li>
  <li>
    The button <span class="btn btn-xs fd-btn-default">Only selected</span> into the <em>Attribute</em> search filters will match only cards with attributes selected on said filter. Best paired with <span class="btn btn-xs fd-btn-default">Only Multi-Attribute</span> to match only multi-attribute cards containing only selected attributes. <em>Ex.: </em> You want fire/wind cards but you definetely don't want to match water/wind cards also.
  </li>
  <li>
    When selecting any attrbute in the <em>Attribute</em> search filter, unless you specifically select the <em>Void</em> icon <img src="<?=fd_asset('images/icons/blank.gif')?>" class="fd-icon-v">, all attribute-less cards are excluded.
  </li>
</ul>