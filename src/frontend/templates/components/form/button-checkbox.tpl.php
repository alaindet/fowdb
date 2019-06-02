<?php
/*
 * INPUT
 * ?css[container, button] array
 * label string
 * name string
 * state bool
 * value string|int
 * 
 * VARIABLES = INPUT
 */

$this->value = $this->value ?? 1;

// State
[$active, $checked] = ($this->state) ? [" active", " checked"] : ["", ""];

// CSS
[$cssContainer, $cssButton] = ["", ""];
if (isset($this->css)) {
  if (isset($this->css["container"])) {
    $cssContainer = " " . implode(" ", $this->css["container"]);
  }
  if (isset($this->css["button"])) {
    $cssButton = " " . implode(" ", $this->css["button"]);
  }
}
?>

<div class="btg-group<?=$cssContainer?>" data-toggle="buttons">
  <label class="btn<?=$cssButton?><?=$active?>">
    <input
      type="checkbox"
      name="<?=$this->name?>"
      value="<?=$this->value?>"
      <?=$checked?>
    >
    <span class="pointer"><?=$this->label?></span>
  </label>
</div>
