<?php

// REQUIRES
// /src/resources/assets/js/dependencies/form/select-multiple.js
// component: select-multiple-handle

// VARIABLES
// $id
// $name
// $items
// $state

?>
<select
  id="<?=$id?>"
  name="<?=$name?>"
  class="form-control"
>
  <option value="no">Select...</option>
  <?php foreach ($items as $key => $item): ?>
    <?php if (is_array($item)): ?>
      <optgroup label="<?=$key?>">
        <?php foreach ($item as $subkey => $subitem): ?>
          <option value="<?=$subkey?>"><?=$subitem?></option>
        <?php endforeach; ?>
      </optgroup>
    <?php else: ?>
      <option value="<?=$key?>"><?=$item?></option>
    <?php endif; ?>
  <?php endforeach; ?>
</select>
