(function () {

  // CSS Selectors ------------------------------------------------------------
  var css_artistsSelectSet = '#js-select-set';


  // Bootstrap ----------------------------------------------------------------
  function bootstrap() {

    $(document)

      // Original events
      .on('change', css_artistsSelectSet, function () {
        var selectedSet = $(this).val();
        $(document).trigger('fd:select-set', [selectedSet]);
      })

      // Custom events
      .on('fd:select-set', handleSelectSetEvent)

  }


  // Controller functions -----------------------------------------------------
  function handleSelectSetEvent(event, set) {
    window.location.href += '/set/' + set;
  }


  // Go! ----------------------------------------------------------------------
  $(document).ready(bootstrap);

})();
