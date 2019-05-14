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
  var css_selectMultiple = '.js-select-multiple';


  // Bootstrap ----------------------------------------------------------------
  function bootstrap() {

    $(document)

      // Original events
      .on('click', css_selectMultiple, function () {
        handleSelectMultipleClick($(this));
      })

      // Custom events
      .on('fd:select-multiple:toggle', handleSelectMultipleEvent)
      .on('fd:select-multiple:reset', handleResetEvent)

  }


  // Controller functions -----------------------------------------------------

  function handleSelectMultipleClick(target) {
    $(document).trigger('fd:select-multiple:toggle', [target]);
  }

  function handleSelectMultipleEvent(event, handle) {

    var target = $(handle.data('target'));
    var isMultiple = !!target.attr('multiple');

    if (isMultiple) view_disableMultiple(target, handle);
    else view_enableMultiple(target, handle);

  }

  function handleResetEvent(event) {

    $(css_selectMultiple).each(function () {

      var handle = $(this);
      var target = $(handle.data('target'));
      view_enableMultiple(target, handle);

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
