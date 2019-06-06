<?php
/**
 * INPUT
 * string url
 * string name
 * ?string defaultOption
 * array items
 * ?string state
 * 
 * VARIABLES = INPUT
 * 
 * NOTES
 * - items can be grouped or non-grouped
 * - grouped items will be wrapped in a <optgroup>
 * - non-grouped items or items from a group are array like [ label => value ]
 * - Grouped ex.: [ "A" => ["Albert" => "alb", "Alain" => "ala"], ... ]
 * - Non-grouped ex.: [ "Albert" => "alb", "Alain" => "ala" ]
 */

$firstKey = array_keys($this->items)[0];
$isGrouped = is_array($this->items[$firstKey]);
$this->state = $this->state ?? "";

?>
<form
  method="get"
  action="<?=$this->url?>"
  class="form form-inline"
>
  <select
    class="form-control"
    name="<?=$this->name?>"
    onchange="this.form.submit()"
  >
    <?php if (isset($this->defaultOption)): // Default option ?>
      <option value=0><?=$this->defaultOption?></option>
    <?php endif; ?>
    
    <?php if ($isGrouped): // Grouped options ?>

      <?php foreach ($this->items as $groupLabel => $item): ?>
        <optgroup label="<?=$groupLabel?>">
          <?php foreach ($item as $itemLabel => $itemValue): ?>
            <option
              value="<?=$itemValue?>"
              <?php if ($itemValue === $this->state): ?>
                selected="selected"
              <?php endif; ?>
            >
              <?=$itemLabel?>
            </option>
          <?php endforeach; ?>
        </optgroup>
      <?php endforeach; ?>

    <?php else: // Non-grouped options ?>

      <?php foreach ($this->items as $itemLabel => $itemValue): ?>
        <option
          value="<?=$itemValue?>"
          <?php if ($itemValue === $this->state): ?>
            selected="selected"
          <?php endif; ?>
        >
          <?=$itemLabel?>
        </option>
      <?php endforeach; ?>

    <?php endif; ?>
  </select>
</form>
