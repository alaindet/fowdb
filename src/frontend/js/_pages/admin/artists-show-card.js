(function () {

  // CSS Selectors ------------------------------------------------------------
  var css_autocompleteInput = '#the-autocomplete';

  // Application data ---------------------------------------------------------
  var data_apiUrl = window.BASE_URL + '/api/artists/autocomplete';


  // Bootstrap ----------------------------------------------------------------
  function bootstrap() {

    $(css_autocompleteInput).autocomplete({
      source: data_apiUrl,
      select: handleSelectedAutocompleteEvent,
      delay: 300,
      minLength: 2
    });

  }


  // Controller functions -----------------------------------------------------
  function handleSelectedAutocompleteEvent(event, ui) {
    event.preventDefault();
    $(css_autocompleteInput).val(ui.item.label);
  }


  // Go! ----------------------------------------------------------------------
  $(document).ready(bootstrap);

})();
