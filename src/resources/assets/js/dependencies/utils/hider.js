(function () {

  // CSS Selectors ----------------------------------------------------------
  const css_hideHandle = '.js-hider';

  // Application data -------------------------------------------------------
  const data_iconWhenOpen = 'fa-chevron-down';
  const data_iconWhenClosed = 'fa-chevron-right';


  // Bootstrap --------------------------------------------------------------
  function bootstrap() {

    $(document)

      // Original events
      .on('click', css_hideHandle, function (event) {
        $(document).trigger('fd:hider', [event, $(this)]);
      })

      // Custom events
      .on('fd:hider', handleHiderEvent)

  }


  // Controller functions ---------------------------------------------------

  function handleHiderEvent(customEvent, originalEvent, handle) {

    const target = handle.data("target");
    const targetElement = $(target);
    const icon = $("i.fa", targetElement);

    if (icon) {
      
      const iconWhenOpen = view_getIconWhenOpen(handle);
      const iconWhenClosed = view_getIconWhenClosed(handle);
      const isOpen = !targetElement.hasClass('hidden');
      const classToRemove = isOpen ? iconWhenOpen : iconWhenClosed;
      const classToAdd = isOpen ? iconWhenClosed : iconWhenOpen;

      $("[data-target='" + target + "']").each(function () {
        $('i.fa', $(this))
          .removeClass(classToRemove)
          .addClass(classToAdd)
      });
      
    }

    // Activate/deactivate any related label handle
    // which is not being clicked
    $(`[data-target="${target}"]`).each(function () {
      const current = $(this)[0];
      if (
        current.nodeName === 'LABEL' && // Is this a <label>?
        !handle.is(current) // Is this being clicked?
      ) {
        $(current).toggleClass('active');
      }
    });


    targetElement.toggleClass("hidden");
  }


  // Model functions --------------------------------------------------------

  // ...


  // View functions ---------------------------------------------------------

  function view_getIconWhenOpen(target) {
    return target.data('open-icon') || data_iconWhenOpen;
  }

  function view_getIconWhenClosed(target) {
    return target.data('closed-icon') || data_iconWhenClosed;
  }


  // Utility functions ------------------------------------------------------

  // ...


  // Go! --------------------------------------------------------------------
  $(document).ready(bootstrap);

})();
