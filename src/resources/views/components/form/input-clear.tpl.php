<?php
// VARIABLES
// $classes
// $name
// $id
// $placeholder
// $autofocus
// $disabled
// $value

// I don't know why the inline styles are needed
?>
<style>
  /* IE-specific, non-standard */
  ::-ms-clear {
    display: none;
  }
  .form-control-clear {
    z-index: 10;
    pointer-events: auto; /* Don't have a clue! */
    cursor: pointer;
  }
</style>
<div
  class="form-group has-feedback has-clear"
  style="margin-right:1rem;"
>
  <input
    type="text"
    class="form-control<?=isset($classes) ? ' '.$classes : ''?>"
    name="<?=$name?>"
    style="margin-left:1rem;"
    id="<?=$id?>"
    placeholder="<?=$placeholder?>"
    <?=isset($autofocus) && $autofocus ? 'autofocus' : ''?>
    <?=isset($disabled) && $disabled ? 'disabled' : ''?>
    <?=isset($value) ? "value=\"{$value}\"" : ''?>
  >
  <span class="text-muted form-control-clear form-control-feedback hidden">
    <i class="fa fa-lg fa-times"></i>
  </span>
</div>
