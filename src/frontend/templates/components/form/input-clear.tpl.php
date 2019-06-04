<?php
/*
 * ==============================================
 * WARNING: This component is under construction!
 * ==============================================
 *
 * INPUT
 * ?classes string
 * name string
 * id string
 * ?placeholder string
 * ?autofocus bool
 * ?disabled bool
 * ?value string
 * 
 * VARIABLES = INPUT 
 * 
 * NOTES
 * I don't know why the inline styles are needed
 * ::-ms-clear is IE-specific
 * I don't know about "pointer-events: auto;" either
 */
?>
<style>
  ::-ms-clear {
    display: none;
  }
  .form-control-clear {
    z-index: 10;
    pointer-events: auto;
    cursor: pointer;
  }
</style>
<div class="form-group has-feedback has-clear" style="margin-right:1rem;">
  <input
    type="text"
    class="form-control<?=isset($this->classes) ? " ".$classes : ""?>"
    name="<?=$this->name?>"
    style="margin-left:1rem;"
    id="<?=$this->id?>"
    <?=isset($this->placeholder) ? "placeholder={$this->placeholder}" : ""?>
    <?=isset($this->autofocus) && $this->autofocus ? "autofocus" : ""?>
    <?=isset($this->disabled) && $this->disabled ? "disabled" : ""?>
    <?=isset($this->value) ? "value=\"{$value}\"" : ""?>
  >
  <span class="text-muted form-control-clear form-control-feedback hidden">
    <i class="fa fa-lg fa-times"></i>
  </span>
</div>
