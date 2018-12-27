/* EXAMPLE USAGE

<div class="input-group js-dropdown-input">

  <!-- Hidden input -->
  <input
    type="hidden"
    name="THE_NAME"
    class="js-dropdown-input-hidden"
    value="THE_VALUE"
  >

  <div class="input-group-btn">
  
    <!-- Dropdown face -->
    <button
      type="button"
      class="btn btn-default dropdown-toggle"
      data-toggle="dropdown"
    >
      <span class="js-dropdown-input-face">
        THE_FACE
      </span>
    </button>

    <!-- Dropdown items -->
    <ul class="dropdown-menu">
      <li>
        <a
          class="js-dropdown-input-item pointer"
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
  var css_dropdownInput = '.js-dropdown-input';
  var css_dropdownInputFace = '.js-dropdown-input-face';
  var css_dropdownInputItem = '.js-dropdown-input-item';
  var css_dropdownInputHidden = '.js-dropdown-input-hidden';


  // Bootstrap ----------------------------------------------------------------
  function bootstrap() {

    $(document)

      // Original events
      .on('click', css_dropdownInputItem, function () {
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
    var container = item.parents(css_dropdownInput);

    // Update hidden input value
    $(css_dropdownInputHidden, container).val(item.data('value'));

    // Update dropdown face
    $(css_dropdownInputFace, container).text(item.data('face'));

  }


  // Go! ----------------------------------------------------------------------
  $(document).ready(bootstrap);

})();
