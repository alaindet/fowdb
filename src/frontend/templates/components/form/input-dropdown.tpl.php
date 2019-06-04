<?php
/*
 * REQUIRES
 * frontend/js/dependencies/form/input-dropdown.js 
 * 
 * INPUT
 * array dropdown {
 *   string name,
 *   ?string state,
 *   array items,
 *   ?array css,
 *   array default: {
 *     string label,
 *     string value
 *   }
 * }
 * array input {
 *   string name,
 *   ?string state,
 *   ?array css,
 *   ?string placeholder,
 *   ?bool autofocus
 * }
 * ?string size
 * ?array css
 * 
 * VARIABLES
 * object dropdown {
 *   string size,
 *   object state: {
 *     string label,
 *     string value
 *   },
 *   object default: {
 *     string label,
 *     string value
 *   }
 * }
 * object input {
 *   string name,
 *   string size,
 *   string state
 *   ?string placeholder
 *   ?bool autofocus
 *   string css
 * }
 * string css
 */
?>
<div class="js-input-dropdown<?=$this->css?>">
  
  <!-- Dropdown hidden input -->
  <input
    type="hidden"
    name="<?=$this->dropdown->name?>"
    class="js-input-dropdown-hidden"
    value="<?=$this->dropdown->state->value?>"
    data-default="<?=$this->dropdown->default->value?>"
  >
  
  
  <div class="input-group">
    <div class="input-group-btn">
      
      <!-- Dropdown button -->
      <button
        type="button"
        class="dropdown-toggle btn fd-btn-default<?=$this->dropdown->css?>"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
      >
        <span
          class="js-input-dropdown-label"
          data-default="<?=$this->dropdown->default->label?>"
        >
          <?=$this->dropdown->state->label?>
        </span>
        <span class="caret"></span>
      </button>

      <!-- Dropdown items -->
      <ul class="dropdown-menu">
        <?php foreach ($this->dropdown->items as $value => $label):
          ($value === $this->dropdown->state->value)
            ? [$active, $selected] = [" active", " selected"]
            :  [$active, $selected] = ["", ""];
        ?>
          <li>
            <a
              class="js-input-dropdown-item pointer"
              data-label="<?=$label?>"
              data-value="<?=$value?>"
            >
              <?=$label?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

    </div>

    <!-- Input -->
    <input
      type="text"
      name="<?=$this->input->name?>"
      class="form-control<?=$this->input->css?>"
      value="<?=$this->input->state?>"
      <?php if (isset($this->input->placeholder)): ?>
        placeholder=<?=$this->input->placeholder?>
      <?php endif; ?>
      <?php if (isset($this->input->autofocus)): ?>
        autofocus
      <?php endif; ?>
    >

  </div>
</div>
