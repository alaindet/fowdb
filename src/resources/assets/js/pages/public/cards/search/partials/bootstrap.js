// console.log('bootstrap.partial.js');

// Bootstrap -------------------------------------------------------------
function bootstrap() {

  // Events name space prefix
  const ns = "fd:cards-search";

  $(document)

    // Original events
    .on("click", css_optionsPanelToggle, () => {
      $(document).trigger("fd:cards-search:resize-panels");
    })
    .on("click", css_searchReset, () => {
      $(document).trigger("fd:cards-search:form-reset");
    })
    .on("submit", css_searchForm, (event) => {
      $(document).trigger("fd:cards-search:form-submit", [event]);
    })
    .on("click", css_itemsPerLineLess, () => {
      $(document).trigger("fd:cards-search:items-per-line:less");
    })
    .on("keyup", css_itemsPerLineInput, function(event) {
      console.log('items-per-line-keyup', event.keyCode);
    })
    .on("click", css_itemsPerLineMore, () => {
      $(document).trigger("fd:cards-search:items-per-line:more");
    })

    // Custom events
    .on(`${ns}:items-per-line:less`, handleItemsPerLineLessEvent)
    .on(`${ns}:items-per-line:more`, handleItemsPerLineMoreEvent)
    .on(`${ns}:items-per-line:fit`, handleItemsPerLineFitEvent)
    .on("fd:cards-search:form-reset", handleFormResetEvent)
    .on("fd:cards-search:form-submit", handleFormSubmitEvent)
    .on("fd:cards-search:resize-panels", handleResizePanelsEvent)

  // Default: fit items into container based on container's size
  $(document).trigger("fd:cards-search:items-per-line:fit");

  // Triggers also when clicking the "Back" button on the browser!
  // console.log('bootstrapping...');
}

$(document).ready(bootstrap);
