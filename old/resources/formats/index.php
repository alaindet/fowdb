<?php
$results = database()
  ->select(
    statement('select')
      ->select([
        'f.name fname',
        'f.code fcode',
        'c.name cname',
        'c.code ccode',
        's.name sname',
        's.code scode',
      ])
      ->from(
        'game_formats f
        INNER JOIN pivot_cluster_format cf ON f.id = cf.formats_id
        INNER JOIN clusters c ON cf.clusters_id = c.id
        INNER JOIN game_sets s ON c.id = s.clusters_id'
      )
      ->orderBy([
        'f.is_multi_cluster DESC',
        'f.id DESC', 
        'c.id DESC',
        's.id DESC'
      ])
  )
  ->get();

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
      <a
        class=" link-as-text"
        href="<?=url_old('', [
          'do' => 'search',
          'format[]' => $f['code']
        ])?>"
      >
        <?=$f['name']?>
      </a>
    </h2>
    <ul class="fdb-indented">
      <?php foreach ($f['list'] as $ccode => &$c): ?>
        <li>
          <h4>
            <a
              class=" link-as-text"
              href="<?=url_old('', [
                'do' => 'search',
                'format[]' => $c['code']
              ])?>"
            >
              <?=$c['name']?>
            </a>
          </h4>
            <ul>
              <?php foreach ($c['list'] as $scode => &$sname): ?>
                <li>
                  <div class="fdb-formats-label">
                    <?=strtoupper($scode)?>
                  </div>
                  <a
                    href="<?=url_old('', [
                      'do' => 'search',
                      'set' => $scode
                    ])?>"
                  >
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
