<?php
  $blank = asset('images/icons/blank.gif');
?>
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

      <!-- Ability separator -->
      <tr>
        <td class="text-monospace">Ability 1&lt;hr&gt;Ability 2</td>
        <td>Ability 1<p></p>Ability 2</td>
        <td>Go to newline (separates abilities)</td>
      </tr>

      <!-- Angle brackets -->
      <tr>
        <td class="text-monospace">&amp;lt;Some named ability&amp;gt;</td>
        <td>&lt;Some named ability&gt;</td>
        <td>Simple angle brackets must be escaped</td>
      </tr>

      <!-- Sealed ability -->
      <tr>
        <td class="text-monospace">
          [(Sealed)],
          &lt;&lt;Sealed&gt;&gt;
        </td>
        <td>【Sealed】, &#12296;Sealed&#12297;</td>
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
        <td class="text-monospace">
          [Break] Break ability&lt;hr&gt;Other text...
        </td>
        <td>
          <span class='fd-mark-break'>Break</span>
          <span class='fd-mark-break-text'>Break ability</span>
          <p></p>
          Other text...
        </td>
        <td>
          Old [Break] ability (deprecated since Grimm Cluster)
        </td>
      </tr>

      <!-- In-text errata -->
      <tr>
        <td class="text-monospace">Some -errata-changed-/errata- text</td>
        <td>Some <span class="fd-mark-errata">changed</span> text</td>
        <td>
          A card text that was corrected via an official errata
        </td>
      </tr>

    </tbody>
  </table>
</div>
