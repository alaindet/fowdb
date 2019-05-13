/* EXAMPLE USAGE

<div class="js-button-dropdown ?--nullable">

  <!-- Dropdown input -->
  <input
    type="hidden"
    name="THE_NAME"
    class="js-button-dropdown-hidden"
    value="THE_DEFAULT_VALUE"
    data-default="THE_DEFAULT_VALUE"
  >

  <!-- Dropdown face -->
  <button
    type="button"
    class="btn btn-default dropdown-toggle"
    data-toggle="dropdown"
  >
    <span
      class="js-button-dropdown-face"
      data-default="THE_DEFAULT_FACE"
    >
      THE_DEFAULT_FACE
    </span>
  </button>

  <!-- Dropdown items -->
  <ul class="dropdown-menu">
    <li>
      <a
        class="js-button-dropdown-item pointer"
        data-face="ITEM_FACE"
        data-value="ITEM_VALUE"
      >
        AN_ITEM
      </a>
    </li>
  </ul>
  
</div>

*/
(function () {

  // CSS Selectors ----------------------------------------------------------
  const css_buttonDropdown = ".js-button-dropdown";
  const css_buttonDropdownFace = ".js-button-dropdown-face";
  const css_buttonDropdownItem = ".js-button-dropdown-item";
  const css_buttonDropdownInput = ".js-button-dropdown-hidden";
  const css_buttonDropdownNullable = ".--nullable";


  // Bootstrap --------------------------------------------------------------
  function bootstrap() {

    $(document)

      // Original events
      .on("click", css_buttonDropdownItem, function () {
        $(document).trigger("fd:button-dropdown:select-item", [$(this)]);
      })

      // Custom events
      .on("fd:button-dropdown:select-item", handleSelectItemEvent)
      .on("fd:button-dropdown:submit", handleSubmitEvent)
      .on("fd:button-dropdown:reset", handleResetEvent)

  }


  // Controller functions ---------------------------------------------------
  function handleSelectItemEvent(event, item) {
    const container = item.parents(css_buttonDropdown);
    view_updateInput(container, item.data('value'));
    view_updateFace(container, item.data('face'));
  }

  /**
   * Disables the dropdown hidden input on submit if .--nullable is set
   * 
   * @param object event 
   */
  function handleSubmitEvent(event) {
    $(css_buttonDropdown).each(function () {
      const container = $(this);
      const input = $(css_buttonDropdownInput, container);
      const nullable = css_buttonDropdownNullable.substr(1); // Remove dot
      if (container.hasClass(nullable) && input.val() === '') {
        const input = $(css_buttonDropdownInput, container);
        input.prop("disabled", true);
      }
    });
  }

  function handleResetEvent(event) {
    $(css_buttonDropdown).each(function () {
      const container = $(this);
      view_updateInput(container);
      view_updateFace(container);
    });
  }


  // Model functions --------------------------------------------------------

  // ...


  // View functions ---------------------------------------------------------

  function view_updateInput(container, value) {
    const element = $(css_buttonDropdownInput, container);
    if (typeof value === 'undefined') value = element.data('default');
    element.val(value);
  }

  function view_updateFace(container, value) {
    const element = $(css_buttonDropdownFace, container);
    if (typeof value === 'undefined') value = element.data('default');
    element.html(value);
  }


  // Utility functions ------------------------------------------------------

  // ...


  // Go! --------------------------------------------------------------------
  $(document).ready(bootstrap);

})();
