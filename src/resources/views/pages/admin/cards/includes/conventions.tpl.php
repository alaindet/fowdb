<div class="well">
	<h3>Rules and Conventions</h3>
	<ul class="fd-list --spaced">
    <li>
      Syntax as seen on <a href="<?=url('cards/search/help#syntax')?>">Cards search help</a>
    </li>
    <li>
      In any text field, <strong>NEVER</strong> enter a new line (hitting Enter), but <strong>ALWAYS</strong> use <code>&lt;hr&gt;</code> to separate different abilities instead
    </li>
		<li>
      Negative free costs are interpreted as X costs, so -1 => X, -2 => XX etc.
		</li>
  </ul>

  <?=include_view('pages/public/cards/includes/syntax-table')?>

</div>
