// Bootstrap ------------------------------------------------------------------
function bootstrap() {

  // Events name space prefix
  const ns = "fd:cards-search";

  $(document)

    // Original events
    // .on("click", css_optionsPanelToggle, () => {
    //   $(document).trigger("fd:cards-search:resize-panels");
    // })
    .on("click", css_itemsPerLineLess, () => {
      $(document).trigger("fd:cards-search:items-per-line:less");
    })
    // Enter key
    .on("keyup", css_itemsPerLineInput, function(event) {
      if (event.keyCode !== data_enterKey) return;
      $(document).trigger(
        "fd:cards-search:items-per-line:fit", [event, event.target.value]
      );
    })
    .on("click", css_itemsPerLineMore, () => {
      $(document).trigger("fd:cards-search:items-per-line:more");
    })

    // Custom events
    .on(`${ns}:items-per-line:less`, handleItemsPerLineLessEvent)
    .on(`${ns}:items-per-line:more`, handleItemsPerLineMoreEvent)
    .on(`${ns}:items-per-line:fit`, handleItemsPerLineFitEvent)
    // .on("fd:cards-search:resize-panels", handleResizePanelsEvent)

  // Default: fit items into container based on container's size
  $(document).trigger("fd:cards-search:items-per-line:fit");
}

$(document).ready(bootstrap);
