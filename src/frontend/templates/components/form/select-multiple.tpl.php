<?php
/*
 * REQUIRES
 * frontend/js/dependencies/form/select-multiple.js
 * 
 * INPUT
 * string name
 * string|string[] state
 * array items
 * ?array css [?array handle=>, ?array select=>]
 * 
 * VARIABLES
 * string name
 * bool isMultiple
 * array items
 * array state
 * object handle {
 *   string css
 *   string target
 * }
 * object select {
 *   string id
 *   bool isGrouped
 *   string css
 * }
 */
?>
<button
  type="button"
  class="js-select-multiple btn<?=$this->handle->css?>"
  data-target="<?=$this->handle->target?>"
>
  Multiple
</button>

<select
  id="<?=$this->select->id?>"
  name="<?=$this->name?>"
  class="form-control<?=$this->select->css?>"
  <?php if ($this->isMultiple): ?>
    multiple="multiple"
    size="10"
  <?php endif; ?>
>
  <option value="">Select...</option>
  <?php foreach ($this->items as $key => $item): ?>
    <?php if ($this->select->isGrouped): ?>

      <optgroup label="<?=$key?>">
        <?php foreach ($item as $subkey => $subitem): ?>
          <option
            value="<?=$subkey?>"
            <?=in_array($subkey, $this->state) ? "selected=\"true\"" : ""?>
          >
            <?=$subitem?>
          </option>
        <?php endforeach; ?>
      </optgroup>

    <?php else: ?>

      <option
        value="<?=$key?>"
        <?=in_array($key, $this->state) ? "selected=\"true\"" : ""?>
      >
        <?=$item?>
      </option>

    <?php endif; ?>
  <?php endforeach; ?>
</select>
