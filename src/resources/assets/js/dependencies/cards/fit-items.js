/**
 * REQUIRES
 * dependencies/utils/is-integer
 * dependencies/utils/range
 * 
 * EXPORTS
 * window.APP.fitItems
 */

/**
 * Changes the size of items inside a container via CSS classes for sizing
 * 
 * The size is decided upon the container's width, but it can optionally be
 * passed as integer
 * 
 * @param string container The container CSS selector
 * @param string item The item CSS selector
 * @param int itemsPerLine (Optional) How many items per line to show
 * @return int Number of items per line
 */
window.APP.fitItems = function(
  container = '.js-fd-card-items',
  item = '.fd-grid',
  itemsPerLine = 0
) {
  const defaultWidth = 220;
  const min = 1;
  const max = 10;

  // Calculate items per row based on the container's width
  if (itemsPerLine === 0) {
    itemsPerLine = Math.floor($(container).width() / defaultWidth);
  }
  
  // Sanitize value anyway
  if (itemsPerLine < min) itemsPerLine = min;
  if (itemsPerLine > max) itemsPerLine = max;

  // Remove dot (Ex.: .fd-grid => fd-grid)
  const itemName = item.substr(1);

  const cssWrongSizes = window.APP
    .range(min, max)
    .map(n => `${itemName}-${n}`)
    .join(' ');

  const cssRightSize = `${itemName}-${itemsPerLine}`;

  // Adjust sizing on all items
  $(item)
    .removeClass(cssWrongSizes)
    .addClass(cssRightSize);

  return itemsPerLine;
};
