/**
 * IMPORTS
 * window.APP.isInteger
 * 
 * EXPORTS
 * window.APP.fitItems
 */

/**
 * Fits card items to the current screen size
 * 
 * @param bool readInput Flag for initializing (skips reading input value)
 */
window.APP.fitItems = function(
  boxSelector,
  itemSelector,
  inputSelector,
  skipInput
) {
  
  // CSS selectors
  var box = boxSelector || '#cards-container';
  var item = itemSelector || '.fdb-card';
  var input = inputSelector || '#opt_i_numxrow';

  // Default data
  var defaultWidth = 200;
  var minItems = 1;
  var maxItems = 10;

  // HTML elements
  var inputEl = $(input);
  var boxEl = $(box);
  var boxWidth = parseInt(boxEl.width());

  // Calculate how many items per row
  if (ini) {
    var itemsPerRow = Math.floor(boxWidth / defaultWidth);
  } else {
    var itemsPerRow = window.APP.isInteger(inputEl.val());
    if (itemsPerRow < minItems) itemsPerRow = minItems;
    if (itemsPerRow > maxItems) itemsPerRow = maxItems;
  }

  // Build classes to remove
  var classesToRemove = window.APP
    .range(minItems, maxItems)
    .map(function(i) { return item + '-' + i; })
    .join(' ');

  // Adjust classes
  $(item)
    .removeClass(classesToRemove)
    .addClass(item + '-' + itemsPerRow);

};
