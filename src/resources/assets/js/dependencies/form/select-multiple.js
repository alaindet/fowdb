/* EXAMPLE USAGE

<!-- The handle -->
<button
  class="js-select-multiple"
  data-target="#the-select"
>
  Multiple
</button>

<!-- The select -->
<select id="the-select" name="some-input">
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
      .on('fd:select-multiple', handleSelectMultipleEvent)

  }


  // Controller functions -----------------------------------------------------

  function handleSelectMultipleClick(target) {
    $(document).trigger('fd:select-multiple', [target]);
  }

  function handleSelectMultipleEvent(event, handle) {

    var target = $(handle.data('target'));
    var isMultiple = !!target.attr('multiple');

    // Enable
    if (isMultiple) {
      target
        .attr('name', target.attr('name').replace('[]', ''))
        .removeAttr('multiple')
        .removeAttr('size');
    }

    // Disable
    else {
      target
        .attr('name', target.attr('name') + '[]')
        .attr('multiple', 'true')
        .attr('size', 10);
    }
  }


  // Go! ----------------------------------------------------------------------
  $(document).ready(bootstrap);

})();
