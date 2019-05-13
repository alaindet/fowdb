/**
 * EXPORTS
 * window.APP.isInteger
 */

/**
 * Checks if an input is an integer or at least numeric
 * If input is not integer nor numeric, returns 0
 * Else returns the input integer as it is
 * 
 * @param any n Any numeric input (even strings)
 * @return int
 */
window.APP.isInteger = (n) => {

  if (Number(parseInt(n)) === parseInt(n) && parseInt(n) % 1 === 0) {
    return parseInt(n);
  }
  
  return 0;

};
