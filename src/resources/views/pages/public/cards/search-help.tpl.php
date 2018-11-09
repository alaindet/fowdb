<?php
$blank = asset('images/icons/blank.gif');
?>
<div class="page-header">
  <h1>Cards search help</h1>
  <?=component('breadcrumb', [
    'Cards' => url_old('/'),
    'Help' => '#'
  ])?>
</div>

<h2>
  <a href="#symbols" name="symbols" class="link-as-text link-hash">
    Symbols
  </a>
</h2>

<div class="table-responsive">
  <table class="table table-striped table-condensed">
    
    <!-- Headings -->
    <thead>
      <th>What you type</th>
      <th>What you get</th>
      <th>Description</th>
    </thead>

    <!-- Body -->
    <tbody>

      <!-- Will symbols -->
      <?php foreach (lookup('attributes.code2name') as $code => $name): ?>
        <tr>
          <td class="text-monospace">{<?=$code?>}</td>
          <td><img src="<?=$blank?>" class="fd-icon-<?=$code?>"></td>
          <td><?=$name?> symbol</td>
        </tr>
      <?php endforeach; ?>

      <!-- Free will -->
      <tr>
        <td class="text-monospace">
          {2}, {x}
        </td>
        <td>
          <span class="fd-icon-free">2</span>,
          <span class="fd-icon-free">x</span>
        </td>
        <td class="text-justified">
          Free will symbol. You can type any number and even "x" between { and }
        </td>
      </tr>

      <!-- Rest -->
      <tr>
        <td class="text-monospace">{rest}</td>
        <td><img src="<?=$blank?>" class="fd-icon-rest"></td>
        <td>Rest symbol</td>
      </tr>

      <!-- Automatic ability arrow -->
      <tr>
        <td class="text-monospace">=&gt;</td>
        <td>&rArr;</td>
        <td>Automatic ability arrow</td>
      </tr>

      <!-- Sealed ability -->
      <tr>
        <td class="text-monospace">[(, )], &lt;&lt;, &gt;&gt;</td>
        <td>【, 】, &#12296;, &#12297;</td>
        <td>Sealed ability symbols</td>
      </tr>

      <!-- Old ability -->
      <tr>
        <td class="text-monospace">[Enter]</td>
        <td><span class="fd-mark-old-ability">Enter</span></td>
        <td>Old ability style (deprecated since Lapis Cluster)</td>
      </tr>

      <!-- New ability -->
      <tr>
        <td class="text-monospace">[_Enter_]</td>
        <td><span class="fd-mark-ability">Enter</span></td>
        <td>
          New ability style. All cards use this style since Lapis Cluster
        </td>
      </tr>

      <!-- Break ability -->
      <tr>
        <td class="text-monospace">[Break] Break ability text</td>
        <td>
          <span class='fd-mark-break'>Break</span>
          <span class='fd-mark-break-text'>Break ability text</span>
        </td>
        <td>
          Old [Break] ability (deprecated since Grimm Cluster)
        </td>
      </tr>

      <!-- In-text errata -->
      <tr>
        <td class="text-monospace">Some -errata-changed text-/errata-</td>
        <td>Some <span class="fd-mark-errata">changed text</span></td>
        <td>
          A card text that was corrected via an official errata
        </td>
      </tr>

    </tbody>
  </table>
</div>

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
