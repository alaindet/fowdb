// Bootstrap ------------------------------------------------------------------
function bootstrap() {

  $(document)

    // Original events
    .on("click", css_searchReset, () => {
      $(document).trigger("fd:cards-search:form-reset");
    })
    .on("submit", css_searchForm, (event) => {
      $(document).trigger("fd:cards-search:form-submit", [event]);
    })

    // Custom events
    .on("fd:cards-search:form-reset", handleFormResetEvent)
    .on("fd:cards-search:form-submit", handleFormSubmitEvent)
}

$(document).ready(bootstrap);
