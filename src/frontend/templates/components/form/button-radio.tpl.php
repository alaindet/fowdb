<?php
/*
 * INPUT
 * name string
 * state string
 * items [ label => value, ... ] array
 * ?css [container=>, button=>] array
 * 
 * VARIABLES = INPUT
 */

// CSS container
if ($this->css !== null && isset($this->css["container"])) {
  $cssContainer = " " . implode(" ", $this->css["container"]);
} else {
  $cssContainer = "";
}

// CSS button
if ($this->css !== null && isset($this->css["button"])) {
  $cssButton = " " . implode(" ", $this->css["button"]);
} else {
  $cssButton = "";
}

?>
<div class="btg-group<?=$cssContainer?>" data-toggle="buttons">
  <?php foreach ($this->items as $value => $label):

      // Preserve 0 character for the label
      if ($label === 0) {
        $label = '&#48;';
      }

      // State
      ($value === $this->state)
        ? [$active, $checked] = [" active", " checked"]
        : [$active, $checked] = ["", ""];
  ?>
    <label class="btn<?=$cssButton?><?=$active?>">
      <input
        type="radio"
        name="<?=$this->name?>"
        value="<?=$value?>"
        <?=$checked?>
      >
      <span class="pointer"><?=$label?></span>
    </label>
  <?php endforeach; ?>
</div>
