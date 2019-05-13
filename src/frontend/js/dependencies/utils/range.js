/**
 * EXPORTS
 * window.APP.range
 */

/**
 * Builds an array range between two integers a and b
 * 
 * @param int a Lower end
 * @param int b Upper end
 * @param int step The distance between each element in the range
 * @return array The range
 */
window.APP.range = (a, b, step = 1) => {
  const range = [];
  for (let i = a; i <= b; i+=step) {
    range.push(i);
  }
  return range;
};
