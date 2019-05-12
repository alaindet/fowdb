<?php

// VARIABLES
// $formats

?>
<div class="page-header">
  <h1>Formats</h1>
</div>
<?php foreach ($formats as $fcode => $f): ?>
  <div class="fdb-format">
    <h2>
      <a
        class=" link-as-text"
        href="<?=fd_url('cards', ['format' => [$f['code']]])?>"
      >
        <?=$f['name']?>
      </a>
    </h2>
    <ul class="ml-100">
      <?php foreach ($f['list'] as $ccode => &$c): ?>
        <li>
          <h4>
            <a
              class=" link-as-text"
              href="<?=fd_url('cards', ['format' => [$c['code']]])?>"
            >
              <?=$c['name']?>
            </a>
          </h4>
            <ul>
              <?php foreach ($c['list'] as $scode => $sname): ?>
                <li>
                  <div class="formats-set-label">
                    <?=strtoupper($scode)?>
                  </div>
                  <a href="<?=fd_url('cards', ['set' => $scode])?>">
                    <?=$sname?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endforeach; ?>
