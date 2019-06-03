<?php
/*
 * INPUT
 * ?name string
 * state array
 * items array
 * ?css [container=>, button=>] array
 * 
 * VARIABLES = INPUT
 * 
 * DESCRIPTION
 * This component draws a group of checkboxes as buttons and has 2 modes,
 * Depending on the $this->name input
 * 
 * 1. Multiple values: Same input name, multiple values
 *    - $this->name is defined
 *    - $this->items = [ value1 => label1, value2 => label2, ... ]
 * 2. Multiple flags: multiple input names, each input is a flag with a label
 *    - $this->name is not defined
 *    - $this->items = [ input_name1 => label1, input_name2, label2, ... ]
 */
$areFlags = !isset($this->name);
$areValues = !$areFlags;

if ($areValues) {
  $this->name .= "[]";
}

// CSS
$cssContainer = "";
$cssButton = "";
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
  <?php
    foreach ($this->items as $key => $value):

      // Preserve 0 character for the label
      if ($value === 0) {
        $value = "&#48;";
      }

      // Multiple flags or multiple values for the same input?
      ($areFlags)
        ? [$inputName, $inputValue] = [$key, 1]
        : [$inputName, $inputValue] = [$this->name, $key];

      // State
      (in_array($key, $this->state))
        ? [$active, $checked] = [" active", " checked"]
        : [$active, $checked] = ["", ""];
  ?>
    <label class="btn<?=$cssButton?><?=$active?>">
      <input
        type="checkbox"
        name="<?=$inputName?>"
        value="<?=$inputValue?>"
        <?=$checked?>
      >
      <span class="pointer"><?=$value?></span>
    </label>
  <?php endforeach; ?>
</div>
