<?php

$string = 'abcdefghijlmn';
$length = 6;

$items = array_reduce(
  str_split($string),
  function ($items, $letter) use ($length) {
    $items['data'][++$items['counter']] = str_repeat($letter, $length);
    return $items;
  },
  ['counter' => 0, 'data' => []]
);
$items = $items['data'];

?>

<!-- The handle -->
<button
  class="btn btn-lg fd-btn-default js-select-multiple"
  data-target="#the-select"
>
  Multiple
</button>

<hr class="fd-hr">

<!-- The select -->
<select
  class="form-control input-lg text-monospace"
  name="param"
  id="the-select"
>
  <?php foreach ($items as $value => $label): ?>
    <option value="<?=$value?>"><?=$label?></option>
  <?php endforeach; ?>
</select>
