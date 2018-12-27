/**
 * EXPORTS
 * window.APP.range
 */

/**
 * Builds an array range between two integers a and b
 * 
 * @param int a Lower end
 * @param int b Upper end
 * @return array The range
 */
window.APP.range = function (a, b, step) {
  var range = [];
  var step = typeof step === 'undefined' ? 1 : step;
  for (i = a; i <= b; i+=step) range.push(i);
  return range;
};
