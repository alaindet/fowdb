/**
 * EXPORTS
 * window.APP.isInteger
 */

/**
 * Checks if an input is an integer or at least numeric and returns an integer
 * for sure. If input is not numeric, returns 0 anyway
 * 
 * @param any n Any numeric input (even strings)
 * @return int
 */
window.APP.isInteger = function(n) {

  if (Number(parseInt(n)) === parseInt(n) && parseInt(n) % 1 === 0) {
    return parseInt(n);
  }
  
  return 0;

};
