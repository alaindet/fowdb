<?php
// VARIABLES
// $targetUrl
// $hidden: [ [name=>, value=>],.. ]
// $label
// $name
// $default
// $items: [ label=>value,.. ] or [ group_label => [ label=>value,.. ],.. ]
?>
<form
  method="get"
  action="<?=url($targetUrl)?>"
  class="form form-inline"
>
  <?php if(isset($hidden)): // Hidden values ?>
    <?php foreach ($hidden as $input): ?>
      <input
        type="hidden"
        name="<?=$input['name']?>"
        value="<?=$input['value']?>"
      >
    <?php endforeach; ?>
  <?php endif; ?>

  <?=$label?>

  <select
    class="form-control"
    name="<?=$name?>"
    onchange="this.form.submit()"
  >
    <?php if (isset($default)): // Default value ?>
      <option value=0><?=$default?></option>
    <?php endif; ?>

    <?php if (is_array($items[0])): ?>
      
      <?php foreach ($items as $item): // Grouped options ?>
        <optgroup label="<?=$key?>">
          <?php foreach ($item as $label => $value): ?>
            <option value="<?=$value?>">
              <?=$label?>
            </option>
          <?php endforeach; ?>
        </optgroup>
      <?php endforeach; ?>

    <?php else: ?>

      <?php foreach ($items as $label => $value): // Single options ?>
        <option value="<?=$value?>">
          <?=$label?>
        </option>
      <?php endforeach; ?>

    <?php endif; ?>

  </select>
</form>
