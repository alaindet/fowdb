<?php

// VARIABLES
// $items

// Format name 2 format code
$formatCodes = array_flip(lookup('formats.code2name'));

?>
<?php foreach ($items as $formatName => $format):
  $formatCode = $formatCodes[$formatName];
?>
  <div class="page-header">
    <h1>

      <!-- Hide handle -->
      <button
        class="btn btn-link link-as-text font-150 js-hider"
        data-target="#<?=$formatCode?>"
        data-open-icon="fa-chevron-down"
        data-closed-icon="fa-chevron-right"
      >
        <i class="fa fa-chevron-down"></i>
      </button>

      <!-- Internal link title -->
      <a
        href="#<?=$formatCode?>"
        name="<?=$formatCode?>"
        class="link-as-text link-hash"
      >
        <?=$formatName?>
      </a>

    </h1>
  </div>

  <div class="col-xs-12" id="<?=$formatCode?>">
    <?php foreach ($format as $deckName => $deck): ?>
      <h2><?=$deckName?></h2>
      <div class="col-xs-12">
        <?php foreach ($deck as $limitedName => $cards): ?>
          <h3><?=$limitedName?></h3>
          <div class="fd-grid-items">
            <?php foreach ($cards as $card): ?>
              <div class="fd-grid-2 fd-grid-sm-3 fd-grid-md-4 p-25">

                <!-- Image -->
                <a href="<?=$card['link']?>">
                  <img src="<?=$card['image']?>" alt="<?=$card['name']?>" />
                </a>

                <ul class="p-50">

                  <!-- Name -->
                  <li><a href="<?=$card['link']?>"><?=$card['name']?></a></li>

                  <!-- Code -->
                  <li><em><?=$card['code']?></em></li>

                </ul>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>

<?php endforeach; ?>
