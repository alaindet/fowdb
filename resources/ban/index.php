<?php
$db = \App\Database::getInstance();

$results = $db->get(
  "SELECT
    formats.name as fname,
    formats.code as fcode,
    cards.cardname as cname,
    cards.cardcode as ccode,
    cards.thumb_path as cimg,
    bans.date as bdate,
    bans.desc as bdesc
  FROM 
    bans
    INNER JOIN formats ON bans.formats_id = formats.id
    INNER JOIN cards ON bans.cards_id = cards.id
  ORDER BY formats_id, bdate desc, cardname asc, setnum desc, cardnum asc, cards.id asc"
);

$items = [];

// Cached format name to gather banned cards from same format
$fmt_cached = '';

foreach ($results as &$r) {

  // Bust the cache
  if ($fmt_cached != $r['fname']) {
    $fmt_cached = $r['fname'];
  }

  // Add current card to itsformat array
  $items[$fmt_cached][] = $r;
}
?>
<!-- Header -->
<div class="page-header">
  <h1>Banned Cards (<?php echo count($results); ?>)</h1>
</div>

<div class="row">
  <?php foreach ($items as $fmt_label => &$fmt): ?>
    <div class="col-xs-12">
      <div class="page-header">
        <a name="<?=strtolower($fmt[0]['fcode'])?>"></a>
        <h2><?=$fmt_label?> (<?=count($fmt)?>)</h2>
      </div>
      <?php foreach ($fmt as &$item): ?>
        <div class="col-xs-6 col-sm-4 col-md-3">
          <div class="thumbnail">
            <a href="/?p=card&code=<?=$item['ccode']?>">
              <img src="<?=$item['cimg']?>" alt="<?=$item['cname']?>">
            </a>
            <div class="caption text-center">
              <a href="/?p=card&code=<?=$item['ccode']?>"><?php
                $name =& $item['cname'];
                echo strlen($name) > 25 ? substr($name, 0, 25).'...' : $name;
              ?></a><br>
              <small><?=$item['ccode']?></small><br>
              <strong><?=$item['bdate']?></strong>
           </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
</div>
