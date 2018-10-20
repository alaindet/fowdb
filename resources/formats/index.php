<?php
// Get db connection
$db = \App\Legacy\Database::getInstance();

// Get data from database
$results = $db->get(
  "SELECT
    formats.name as fname,
    formats.code as fcode,
    clusters.name as cname,
    clusters.code as ccode,
    sets.name as sname,
    sets.code as scode
  FROM
    formats
    INNER JOIN formats_clusters
      ON formats.id = formats_clusters.formats_id
    INNER JOIN clusters
      ON formats_clusters.clusters_id = clusters.id
    INNER JOIN sets
      ON clusters.id = sets.clusters_id
    ORDER BY
      formats.ismulticluster DESC,
      formats.id DESC, 
      clusters.id DESC,
      sets.id DESC"
);

// Will hold final data
$formats = [];
// Chache values
$cache_format = '';
$cache_cluster = '';
// Temporary arrays
$format = [];
$cluster = [];

// Loop on database results to format data
foreach ($results as &$r) {

  // Check cached format
  if ($cache_format != $r['fcode']) {
    $cache_format = $r['fcode'];
    $formats[$cache_format]['name'] = $r['fname']; // Save name
    $formats[$cache_format]['code'] = $r['fcode']; // Save code
  }

  // Check cached cluster
  if ($cache_cluster != "c-".$r['ccode']) {
    $cache_cluster = "c-".$r['ccode'];
    $formats[$cache_format]['list'][$cache_cluster]['name'] = $r['cname']; // Save name
    $formats[$cache_format]['list'][$cache_cluster]['code'] = "c-".$r['ccode']; // Save code
  }

  // Add item to formats
  $formats[$cache_format]['list'][$cache_cluster]['list'][$r['scode']] = $r['sname'];
}
?>
<?php foreach ($formats as $fcode => &$f): ?>
  <div class="fdb-format">
    <h2>
      <a href="/?do=search&format=<?=$f['code']?>" class="no-style"><?=$f['name']?></a>
    </h2>
    <ul class="fdb-indented">
      <?php foreach ($f['list'] as $ccode => &$c): ?>
        <li>
          <h4>
            <a href="/?do=search&format=<?=$c['code']?>" class="no-style"><?=$c['name']?></a>
          </h4>
            <ul>
              <?php foreach ($c['list'] as $scode => &$sname): ?>
                <li>
                  <div class="fdb-formats-label"><?=strtoupper($scode)?></div>
                  &nbsp;
                  <a href="/?do=search&setcode=<?=$scode?>"><?=$sname?></a>
                </li>
              <?php endforeach; ?>
            </ul>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endforeach; ?>
