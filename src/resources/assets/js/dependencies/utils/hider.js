(function () {

  // CSS Selectors ------------------------------------------------------------
  var css_hideHandle = '.js-hider';

  // Application data ---------------------------------------------------------
  var data_iconWhenOpen = 'fa-chevron-down';
  var data_iconWhenClosed = 'fa-chevron-right';


  // Bootstrap ----------------------------------------------------------------
  function bootstrap() {

    $(document)

      // Original events
      .on('click', css_hideHandle, function () {
        handleHideHandleClick($(this));
      })

      // Custom events
      .on('fd:hider', handleHiderEvent)

  }


  // Controller functions -----------------------------------------------------

  function handleHideHandleClick(target) {
    $(document).trigger('fd:hider', [target]);
  }

  function handleHiderEvent(event, handle) {

    var target = handle.data('target');
    var targetElement = $(target);
    var icon = $('i.fa', target);

    if (icon) {
      var iconWhenOpen = view_getIconWhenOpen(handle);
      var iconWhenClosed = view_getIconWhenClosed(handle);
      var isOpen = !targetElement.hasClass('hidden');
      var classToRemove = isOpen ? iconWhenOpen : iconWhenClosed;
      var classToAdd = isOpen ? iconWhenClosed : iconWhenOpen;

      $("[data-target='" + target + "']").each(function () {
        $('i.fa', $(this))
          .removeClass(classToRemove)
          .addClass(classToAdd)
      });
    }

    targetElement.toggleClass("hidden");
  }


  // Model functions ----------------------------------------------------------

  // ...


  // View functions -----------------------------------------------------------

  function view_getIconWhenOpen(target) {
    return target.data('open-icon') || data_iconWhenOpen;
  }

  function view_getIconWhenClosed(target) {
    return target.data('closed-icon') || data_iconWhenClosed;
  }


  // Utility functions --------------------------------------------------------

  // ...


  // Go! ----------------------------------------------------------------------
  $(document).ready(bootstrap);

})();
