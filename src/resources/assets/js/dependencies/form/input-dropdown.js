/* EXAMPLE USAGE

<div class="js-input-dropdown">

  <!-- Dropdown input -->
  <input
    type="hidden"
    name="DROPDOWN_NAME"
    class="js-input-dropdown-hidden"
    value="DROPDOWN_VALUE"
    data-default="DROPDOWN_DEFAULT_VALUE"
  >

  <div class="input-group">
    <div class="input-group-btn">

      <!-- Dropdown button -->
      <button
        type="button"
        class="dropdown-toggle btn fd-btn-default"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
      >
        <span
          class="js-input-dropdown-face"
          data-default="DROPDOWN_DEFAULT_FACE"
        >
          DROPDOWN_FACE
        </span>
        <span class="caret"></span>
      </button>

      <!-- Dropdown items -->
      <ul class="dropdown-menu">
        <li>
          <a
            class="js-input-dropdown-item pointer"
            data-face="DROPDOWN_ITEM_FACE"
            data-value="DROPDOWN_ITEM_VALUE"
          >
            DROPDOWN_ITEM_FACE
          </a>
        </li>
        ...
      </ul>

    </div>

    <!-- Input -->
    <input
      type="text"
      name="INPUT_NAME"
      class="form-control"
      value="INPUT_VALUE"
    >

  </div>

</div>

*/
(function () {

  // CSS Selectors ------------------------------------------------------------
  var css_inputDropdown = '.js-input-dropdown';
  var css_inputDropdownFace = '.js-input-dropdown-face';
  var css_inputDropdownItem = '.js-input-dropdown-item';
  var css_inputDropdownInput = '.js-input-dropdown-hidden';


  // Bootstrap ----------------------------------------------------------------
  function bootstrap() {

    $(document)

      // Original events
      .on('click', css_inputDropdownItem, function () {
        handleDropdownItemClick($(this));
      })

      // Custom events
      .on('fd:input-dropdown:select-item', handleSelectItemEvent)
      .on('fd:input-dropdown:reset', handleResetEvent)

  }


  // Controller functions -----------------------------------------------------

  function handleDropdownItemClick(target) {
    $(document).trigger('fd:input-dropdown:select-item', [target]);
  }

  function handleSelectItemEvent(event, item) {
    var container = item.parents(css_inputDropdown);
    view_updateInput(container, item.data('value'));
    view_updateFace(container, item.data('face'));
  }

  function handleResetEvent(event) {
    $(css_inputDropdown).each(function () {
      var container = $(this);
      view_updateInput(container);
      view_updateFace(container);
    });
  }


  // Model functions ----------------------------------------------------------

  // ...


  // View functions -----------------------------------------------------------

  function view_updateInput(container, value) {
    var element = $(css_inputDropdownInput, container);
    if (typeof value === 'undefined') value = element.data('default');
    element.val(value);
  }

  function view_updateFace(container, value) {
    var element = $(css_inputDropdownFace, container);
    if (typeof value === 'undefined') value = element.data('default');
    element.html(value);
  }


  // Utility functions --------------------------------------------------------

  // ...


  // Go! ----------------------------------------------------------------------
  $(document).ready(bootstrap);

})();
