<!-- Rules -->
<div class="fd-box --darker-headings --more-margin">
  <div class="fd-box__title"><h2>Rules</h2></div>
  <div class="fd-box__content">
    <ul class="fd-list --spaced">
      <li>
        In any text field, <strong>NEVER</strong> enter a new line (hitting Enter), but <strong>ALWAYS</strong> use <code>&lt;hr&gt;</code> to separate different abilities instead
      </li>
      <li>
        In the rare case of a <em>battling card</em> (J-Ruler or Resonator) with <strong>no ATK and DEF printed values</strong> (Ex.: <em>Refrain, Child of Convergence (TTW-063 R)</em>), just enter <code>-1</code> on both ATK and DEF inputs!
      </li>
      <li>
        Required fields
        <ul class="fd-list">
          <li>Image</li>
          <li>NARP</li>
          <li>Set</li>
          <li>Number</li>
          <li>Back side</li>
          <li>Code</li>
          <li>Name</li>
        </ul>
      </li>
      <li>
        Optional fields
        <ul class="fd-list">
          <li>Rarity (Reset: <code>(None)</code>)</li>
          <li>Attribute (Reset: Select only <code>(NO)</code>)</li>
          <li>Backside (Reset: <code>(Basic)</code>)</li>
          <li>Attribute cost (Reset: <code>-1</code>)</li>
          <li>Free cost (Reset: <code>-1</code>)</li>
          <li>Divinity cost (Reset: <code>-1</code>)</li>
          <li>ATK (Reset: <code>-1</code>)</li>
          <li>DEF (Reset: <code>-1</code>)</li>
          <li>Race/Trait (Reset: <code>-1</code>)</li>
          <li>Text (Reset: <code>-1</code>)</li>
          <li>Flavor text (Reset: <code>-1</code>)</li>
          <li>Artist name (Reset: <code>-1</code>)</li>
        </ul>
      </li>
    </ul>
  </div>
</div>

<!-- Syntax -->
<div class="fd-box --darker-headings --more-margin">
  <div class="fd-box__title"><h2>Syntax</h2></div>
  <div class="fd-box__content">
    <p>
      As seen on <a href="<?=url('cards/search/help#syntax')?>">Cards search help</a>
    </p>
    <?=include_view('pages/public/cards/includes/syntax-table')?>
  </div>
</div>
