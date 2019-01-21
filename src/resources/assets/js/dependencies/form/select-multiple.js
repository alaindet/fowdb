/* EXAMPLE USAGE

<!-- The handle -->
<button
  class="js-select-multiple"
  data-target="#the-select"
>
  Multiple
</button>

<!-- The select -->
<select
  id="the-select"
  name="some-input"
>
  <option value="...">...</option>
  ...
</select>

*/
(function () {

  // CSS Selectors ------------------------------------------------------------
  var css_multipleHandle = '.js-select-multiple';


  // Bootstrap ----------------------------------------------------------------
  function bootstrap() {

    $(document)

      // Original events
      .on('click', css_multipleHandle, function (event) {
        $(document).trigger('fd:select-multiple:toggle', [event, $(this)]);
      })

      // Custom events
      .on('fd:select-multiple:toggle', handleSelectMultipleEvent)
      .on('fd:select-multiple:reset', handleResetEvent)

  }


  // Controller functions -----------------------------------------------------

  function handleSelectMultipleEvent(customEvent, originalEvent, handle) {

    const targetId = handle.data('target');
    const target = $(targetId);
    const isMultiple = !!target.attr('multiple');

    if (isMultiple) {
      view_disableMultiple(target, handle);
    } else {
      view_enableMultiple(target, handle);
    }

  }

  function handleResetEvent(customEvent) {

    // Loop on all multiple handles and disable all select-multiple components
    $(css_multipleHandle).each(function () {
      const handle = $(this);
      const targetId = handle.data('target');
      const target = $(targetId);
      view_disableMultiple(target, handle);
    });

  }


  // Model functions ----------------------------------------------------------

  // ...


  // View functions -----------------------------------------------------------

  function view_enableMultiple(target, handle) {
    handle
      .addClass('active');
    target
      .attr('name', target.attr('name') + '[]')
      .attr('multiple', 'true')
      .attr('size', 10);
  }

  function view_disableMultiple(target, handle) {
    handle
      .removeClass('active');
    target
      .attr('name', target.attr('name').replace('[]', ''))
      .removeAttr('multiple')
      .removeAttr('size');
  }


  // Utility functions --------------------------------------------------------

  // ...


  // Go! ----------------------------------------------------------------------
  $(document).ready(bootstrap);

})();
