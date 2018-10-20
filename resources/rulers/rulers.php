<?php

// Database connection
$db = \App\Legacy\Database::getInstance();

// Get clusters from helper files
$clusters = \App\Legacy\Helpers::get("clusters");

// Get last set
$lastClusterName = array_keys($clusters)[0];
$lastCluster =& $clusters[$lastClusterName];
$lastSet = array_keys($lastCluster['sets'])[0];

// Init rulers count
$rulers_count = 0;

// If user passed a set, use it, otherwise get latest set
// (Cluster helper is in reverse order both for clusters and sets)
(isset($_GET['set']) AND $_GET['set'] != '0')
    ? $rulers_set = $_GET['set']
    : $rulers_set = $lastSet;

// Build SQL
$rulers = $db->get(
    "SELECT cardname, cardcode, cardtype, atk, def, cardtext
    FROM cards
    WHERE setcode = :setcode AND cardtype IN('Ruler', 'J-Ruler')
    ORDER BY cardnum",
    [":setcode" => $rulers_set]
);

// Check for rulers         
if (empty($rulers)) {

    // Add notification
    notify("No rulers for this set. Please select another set from the menu.", "warning");

// There were rulers for this set
} else {

    // Loop into results to render text and count rulers
    foreach ($rulers as $key => &$r) {

        // Render text
        $rulers[$key]["cardtext"] = render($r['cardtext']);

        // Count rulers
        if ($r['cardtype'] == 'Ruler') {
            $rulers_count++;
        }
    }
}
?>

<!-- Header -->
<div class="page-header">
  <h1>
    Rulers
    <small>(set <?=strtoupper($rulers_set)?>, count <?=count($rulers)?>)</small>
  </h1>
</div>

<!-- Change set -->
<div class="row">
  <div class="col-xs-12">
    <form class="form form-inline" method="GET">
      <input type="hidden" name="p" value="rulers">
      <select class="form-control input-sm" name="set" onchange="this.form.submit()">
        <option value=0>Last set (default)</option>
        <?php foreach($clusters as $cid => $cluster): ?>
          <optgroup label="<?=$cluster['name']?>">
            <?php foreach($cluster['sets'] as $setcode => &$setname):
              $checked = ($setcode == $rulers_set) ? ' selected=true' : '';
            ?>
              <option value="<?=$setcode?>"<?=$checked?>>
                <?=$setname?> - (<?=strtoupper($setcode)?>)
              </option>;
            <?php endforeach; ?>
          </optgroup>
        <?php endforeach; ?>    
      </select>
    </form>
  </div>
</div>

<hr>

<?php // Download button
    $filename = "/resources/rulers/files/rulers_{$rulers_set}.pdf";
    if (file_exists(DIR_ROOT.$filename)):
?>
  <div class="row">
    <div class="col-xs-12">
      <a href="<?=$filename?>" class="btn btn-info">
        <span class="glyphicon glyphicon-save"></span>&nbsp;Download PDF
      </a>
    </div>
  </div>
  <hr>
<?php endif; ?>

<!-- RULERS -->
<div class="row">
  <div class="col-xs-12">
    <?php if (!empty($rulers)): ?>
      <table class="table table-striped table-condensed ruling-table">
        <tbody>
          <?php foreach ($rulers as &$ruler): ?>
            <?php
              // Replace spaces in card code to $nbsp; to avoid line breaks
              $ruler['cardcode'] = str_replace(" ", "&nbsp;", $ruler['cardcode']);

              // Assemble link to the card page
              $card_link = '?p=card&code='.str_replace("&nbsp;", "+", $ruler['cardcode']);
            ?>
            <tr>
              <!-- Name, code, type -->
              <td>
                <a href="<?=$card_link?>" target="_blank"><?=$ruler['cardname']?></a>
                <br><?=$ruler['cardcode']?>
                <br><?=$ruler['cardtype']?>
                <br><?php if (!empty($ruler['def'])): ?>
                    ATK <?=$ruler['atk']?> / DEF <?=$ruler['def']?>
                <?php endif; ?>
              </td>

              <!-- Card text -->
              <td><?=$ruler['cardtext']?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
