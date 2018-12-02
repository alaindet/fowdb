<?php

$rawData = \App\Models\PlayRestriction::getData($page = 'banlist');
$items = \App\Views\PlayRestriction::display($rawData, 'all');

if (empty($items)) {
  alert(
    'There are no banned or limited cards on FoWDB at the moment',
    'warning'
  );
  redirect_old('/');
}

?>

<?php foreach ($items as $typeLabel => $typeList): ?>

  <h1><?=$typeLabel?></h1>

  <div class="col-xs-12">

    <?php foreach ($typeList as $deckLabel => $deckList): ?>

      <h2><?=$deckLabel?></h2>

      <div class="col-xs-12">

        <?php foreach ($deckList as $formatLabel => $formatList): ?>

          <h3><?=$formatLabel?></h3>

          <div class="fd-grid-items">

            <?php foreach ($formatList as $card): ?>
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
