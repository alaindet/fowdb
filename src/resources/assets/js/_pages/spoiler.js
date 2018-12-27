(function () {

  // CSS Selectors ------------------------------------------------------------
  var app_css_optionsHider = '.js-hider[data-target="#hide-options"]';
  var app_css_showMissing = '#opt_i_missing';
  var app_css_spoiler = '.spoiler';
  var app_css_spoilerBody = '.spoiler-body';
  var app_css_card = '.fdb-card';
  var app_css_missingCard = '.fdb-card-missing';

  // Application data ---------------------------------------------------------
  // // TO DO: Implement PHP's custom asset() function in JavaScript also
  var app_data_mobileBreakpoint = 768;
  var app_data_blankImage = 'images/in-pages/search/more.jpg';
  var app_data_cardPattern = /fdb-card-/;
  var app_data_displayClass = 'fdb-card-3';


  // Controller Functions -----------------------------------------------------
  function bootstrap() {
    // Show Options side panel on desktops
    // view_toggleOptionsPanel(window.innerWidth);

    // Show/Hide missing spoiler cards
    $(document)

      // Basic event: Show/Hide missing spoiler cards
      .on(
        'click',
        app_css_showMissing,
        function () {
          handleShowMissingClick($(this));
        })

      // Custom events
      .on('my:show-missing', handleShowMissingEvent)
      .on('my:hide-missing', handleHideMissingEvent)
  }

  function handleShowMissingClick(clickedButton) {
    // Loop on each spoiler set
    $(app_css_spoiler).each(function () {

      // Select elements and read some data for this spoiler set
      var spoilerSection = $(this);
      var spoilerBody = $(app_css_spoilerBody, spoilerSection);
      var spoilerCode = spoilerSection.data('set-code').toUpperCase();
      var spoilerCount = parseInt(spoilerSection.data('set-count'));
      app_data_displayClass = view_readDisplayClass(app_css_card);

      // Clicking...
      if (!clickedButton.hasClass('active')) {
        $(document).trigger('my:show-missing', [
          spoilerBody,
          spoilerCount,
          spoilerCode
        ]);
      }

      // De-clicking...
      else {
        $(document).trigger('my:hide-missing', [
          spoilerBody
        ]);
      }

    });

  }

  /**
   * Custom event handler
   * Shows unspoiled (missing) cards as blank cards
   * 
   * @param object event The custom event
   * @param object spoilerBody The cards container for this spoiler set
   */
  function handleShowMissingEvent(event, spoilerBody, spoilerCount, spoilerCode) {
    // Read existing cards' numbers
    var nums = view_readExistingNumbers(app_css_card, spoilerBody);

    // Calculate missing numbers
    var missingNums = model_calculateMissingNumbers(nums, spoilerCount);

    // Build missing cards HTML
    var missingHtml = missingNums.reduce(function (result, missing) {
      return result += view_blankCard(missing, spoilerCode);
    }, '');

    // Add all missing cards to DOM
    spoilerBody.append(missingHtml);

    // Sort missing and existing cards
    view_sortCards(app_css_card, spoilerBody);
  }

  function handleHideMissingEvent(event, spoilerBody) {
    $(app_css_missingCard).remove();
    view_restoreOriginalSorting(app_css_card, spoilerBody);
  }


  // View Functions -----------------------------------------------------------

  /**
   * Toggles the Options side panel if visiting from a desktop
   * 
   * @param int resolutionX The horizontal resolution to be checked upon
   * @return void
   */
  function view_toggleOptionsPanel(resolutionX) {
    if (resolutionX > app_data_mobileBreakpoint) {
      $(app_css_optionsHider).click();
    }
  }

  /**
   * Fetches the current "display class" of cards
   * 
   * Ex.: "fdb-card-8", which means 8 cards in a row are visible => width=12.5%
   * 
   * @param string itemSelector The card CSS selector
   * @return string The current display class
   */
  function view_readDisplayClass(itemSelector) {
    var classes = $(itemSelector).first().attr('class').split(' ');
    return classes.find(function (i) { return i.match(app_data_cardPattern) });
  }

  /**
   * Reads card numbers from the view
   * 
   * @param string itemSelector 
   * @param object containerElement 
   */
  function view_readExistingNumbers(itemSelector, containerElement) {
    var nums = [];

    // Read all missing cards' numbers
    $(itemSelector, containerElement).each(function () {
      var num = parseInt($(this).data('number'));

      // Check needed for unspoiled Ruler/J-Ruler and shift cards with same num
      if (nums.indexOf(num) === -1) nums.push(num);
    });

    return nums.sort(function (a, b) { return a - b; });
  }

  /**
   * Returns a missing card element as HTML
   * 
   * @param int missing The current missing card number
   * @param string code The spoiler set code
   * @return string The HTML of the missing card element
   */
  function view_blankCard(missing, code) {
    return [
      '<div ',
      'class="fdb-card ', app_data_displayClass, ' fdb-card-missing" ',
      'data-number="', missing, '" ',
      'data-code="', code, '-', util_padNumber(missing, 3), '"',
      '>',
      '<span class="fdb-card-missing-label">', missing, '</span>',
      '<img src="', app_data_blankImage, '">',
      '</div>'
    ].join('');
  }

  /**
   * Sorts cards by their code inside their container
   * Code is loosely based on a card's number. Ascending order
   * 
   * @param object container Contains cards from the spoiler set
   * @return void
   */
  function view_sortCards(itemSelector, container) {
    $(itemSelector, container).sort(function (a, b) {
      return $(a).data('code') < $(b).data('code') ? -1 : 1;
    })
      .appendTo(container);
  }

  function view_restoreOriginalSorting(itemSelector, container) {
    $(itemSelector, container).sort(function (a, b) {
      return $(a).data('id') < $(b).data('id') ? 1 : -1;
    })
      .appendTo(container);
  }


  // Model Functions ----------------------------------------------------------

  /**
   * Calculates missing numbers as "holes" in a given sequence with a max value
   * 
   * @param array nums
   * @param int max
   */
  function model_calculateMissingNumbers(nums, max) {
    var missing = [];

    for (var i = 1; i <= max; i++) {
      if (nums.indexOf(i) === -1) missing.push(i);
    }

    return missing;
  }


  // Utility Functions --------------------------------------------------------
  // TODO: They should be abstracted in their own utilities file

  /**
   * Adds leading zeros (or, optionally, something else) to a number
   * 
   * @param int number
   * @param int len
   * @param string filler Defaults to 0
   * @return string The left-padded number
   */
  function util_padNumber(number, len, filler) {
    filler = filler || '0';
    string = number + '';
    while (string.length < len) string = filler + string;
    return string;
  }

  // Bootstrap the application ------------------------------------------------
  $(document).ready(bootstrap);

})();
