<?php

// REQUIRES
// /src/resources/assets/js/dependencies/form/input-dropdown.js

// VARIABLES
// $dropdown[]
//   dropdown.name
//   dropdown.items
//   dropdown.css[] (optional)
//   dropdown.default[]
//     dropdown.default.face
//     dropdown.default.value
//   dropdown.state[]
//     dropdown.state.face
//     dropdown.state.value
// $input[]
//   input.name
//   input.state
//   input.css[] (optional)

$dropdownCss = isset($dropdown['css']) ? ' '.implode($dropdown['css']) : '';
$inputCss = isset($input['css']) ? ' '.implode($input['css']) : '';

?>
<div class="js-input-dropdown">

  <!-- Dropdown hidden input -->
  <input
    type="hidden"
    name="<?=$dropdown['name']?>"
    class="js-input-dropdown-hidden"
    value="<?=$dropdown['state']['value']?>"
    data-default="<?=$dropdown['default']['value']?>"
  >

  <div class="input-group">
    <div class="input-group-btn">

      <!-- Dropdown button -->
      <button
        type="button"
        class="dropdown-toggle btn fd-btn-default<?=$dropdownCss?>"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
      >
        <span
          class="js-input-dropdown-face"
          data-default="<?=$dropdown['default']['face']?>"
        >
          <?=$dropdown['state']['face']?>
        </span>
        <span class="caret"></span>
      </button>

      <!-- Dropdown items -->
      <ul class="dropdown-menu">
        <?php foreach ($dropdown['items'] as $value => $face):

          // Sticky
          [$active, $selected] = ['', ''];
          if ($value === $dropdown['state']['value']) {
            [$active, $selected] = [' active', ' selected'];
          }

        ?>
          <li>
            <a
              class="js-input-dropdown-item pointer"
              data-face="<?=$face?>"
              data-value="<?=$value?>"
            >
              <?=$face?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

    </div>

    <!-- Input -->
    <input
      type="text"
      name="<?=$input['name']?>"
      class="form-control<?=$inputCss?>"
      value="<?=$input['state']?>"
    >

  </div>
</div>
