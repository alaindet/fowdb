<?php

// REQUIRES
// /src/resources/assets/js/dependencies/form/select-multiple.js
// component: select-multiple-handle

// VARIABLES
// $css (optional)
// $id
// $items
// $name
// $state (optional)

// CSS
$css = isset($css) ? ' '.implode(' ', $css) : '';

// STATE
if (isset($state) && is_array($state)) {
  $brackets = "[]";
  $multiple = ' multiple="multiple" size="10"';
} else {
  $brackets = '';
  $multiple = '';
  $state = [$state];
}

?>
<select
  id="<?=$id?>"
  name="<?=$name?><?=$brackets?>"
  class="form-control<?=$css?>"
  <?=$multiple?>
>
  <option value="">Select...</option>
  <?php foreach ($items as $key => $item): ?>
    <?php if (is_array($item)): ?>
      <optgroup label="<?=$key?>">
        <?php foreach ($item as $subkey => $subitem):
          $checked = in_array($subkey, $state) ? ' selected="selected"' : '';
        ?>
          <option value="<?=$subkey?>"<?=$checked?>><?=$subitem?></option>
        <?php endforeach; ?>
      </optgroup>
    <?php else:
      $checked = in_array($key, $state) ? ' selected="true"' : '';
    ?>
      <option value="<?=$key?>"<?=$checked?>><?=$item?></option>
    <?php endif; ?>
  <?php endforeach; ?>
</select>
