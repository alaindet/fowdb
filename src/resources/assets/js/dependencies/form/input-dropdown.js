/* EXAMPLE USAGE

<div class="input-group js-input-dropdown">

  <!-- Hidden input -->
  <input
    type="hidden"
    name="THE_NAME"
    class="js-input-dropdown-hidden"
    value="THE_VALUE"
  >

  <div class="input-group-btn">
  
    <!-- Dropdown face -->
    <button
      type="button"
      class="btn btn-default dropdown-toggle"
      data-toggle="dropdown"
    >
      <span class="js-input-dropdown-face">
        THE_FACE
      </span>
    </button>

    <!-- Dropdown items -->
    <ul class="dropdown-menu">
      <li>
        <a
          class="js-input-dropdown-item pointer"
          data-face="ITEM_FACE"
          data-value="ITEM_VALUE"
        >
          AN_ITEM
        </a>
      </li>
    </ul>

  </div>

</div>

*/
(function () {

  // CSS Selectors ------------------------------------------------------------
  var css_inputDropdown = '.js-input-dropdown';
  var css_inputDropdownFace = '.js-input-dropdown-face';
  var css_inputDropdownItem = '.js-input-dropdown-item';
  var css_inputDropdownHidden = '.js-input-dropdown-hidden';


  // Bootstrap ----------------------------------------------------------------
  function bootstrap() {

    $(document)

      // Original events
      .on('click', css_inputDropdownItem, function () {
        handleDropdownItemClick($(this));
      })

      // Custom events
      .on('fd:select-dropdown-item', handleSelectItemEvent)

  }


  // Controller functions -----------------------------------------------------

  function handleDropdownItemClick(target) {
    $(document).trigger('fd:select-dropdown-item', [target]);
  }

  function handleSelectItemEvent(event, item) {

    // Select container element
    var container = item.parents(css_inputDropdown);

    // Update hidden input value
    $(css_inputDropdownHidden, container).val(item.data('value'));

    // Update dropdown face
    $(css_inputDropdownFace, container).text(item.data('face'));

  }


  // Go! ----------------------------------------------------------------------
  $(document).ready(bootstrap);

})();
