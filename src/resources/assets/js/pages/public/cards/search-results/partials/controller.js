function handleItemsPerLineLessEvent() {
  data_itemsPerLine = view_readItemsPerLineInput() - 1;
  $(document).trigger(
    "fd:cards-search:items-per-line:fit",
    [{}, data_itemsPerLine]
  );
}

function handleItemsPerLineMoreEvent() {
  data_itemsPerLine = view_readItemsPerLineInput() + 1;
  $(document).trigger(
    "fd:cards-search:items-per-line:fit",
    [{}, data_itemsPerLine]
  );
}

function handleItemsPerLineFitEvent(
  customEvent,
  originalEvent,
  itemsPerLine,
) {
  data_itemsPerLine = window.APP.fitItems(
    css_cardsContainer,
    css_cardItem,
    itemsPerLine
  );
  view_setItemsPerLineInput(data_itemsPerLine);
}

function handleFormResetEvent() {
  
  // Reset all text inputs
  $("input[type='text']", css_searchForm).val('');

  // Uncheck all checkbox buttons. Works also on components:
  // - form/button-checkbox
  // - form/button-checkboxes
  $("label.btn.active", css_searchForm).each(function() {
    const button = $(this);
    const input = $("input", button);
    button.removeClass("active");
    input.prop("checked", false).removeAttr("checked");
  });

  // Reset form/button-dropdown components
  $(document).trigger("fd:button-dropdown:reset");

  // Reset all form/select-multiple components
  $(document).trigger("fd:select-multiple:reset")

  // Reset all form/input-dropdown components
  $(document).trigger("fd:input-dropdown:reset")

  // Re-activate default buttons
  for (const filter in data_defaultFilters) {
    const filterValue = data_defaultFilters[filter];
    const filterName = filter + (filterValue instanceof Array ? "[]" : "");
  }

  // Activate default filters
  data_defaultFilters.forEach((filter) => {

    // // Checkbox buttons
    // if (filter.type === 'checkbox') {
    //   filter.value.forEach(value => {
    //     view_activateButtons(filter.name, value);
    //   });
    // }

    // Radio buttons
    if (filter.type === 'radio') {
      view_activateButtons(filter.name, filter.value);
    }

  });

}

function handleFormSubmitEvent(customEvent, originalEvent) {

  // Custom checks on form/input-dropdown components
  // Prevent unselected dropdowns and empty inputs
  $(document).trigger("fd:input-dropdown:submit");

  // Custom checks on form/button-dropdown components
  // Prevent unselected dropdowns
  $(document).trigger("fd:button-dropdown:submit");

  // Disable all empty <input> (type: text)
  $(`${css_searchForm} input[type='text']`).each(function() {
    const input = $(this);
    if (input.val() === '') input.prop('disabled', true);
  });

  // Disable all empty <select>
  $(`${css_searchForm} select`).each(function () {
    const select = $(this);
    if (select.val() === '') {
      $('option:first-child', select).prop('disabled', true);
    }
  });

  // Prevent submitting the form
  // originalEvent.preventDefault();

}

function handleResizePanelsEvent() {
  if (window.innerWidth <= data_breakpoint) return;
  $(css_optionsPanel).toggleClass("col-sm-3");
  $(css_resultsPanel).toggleClass("col-sm-9");
}
