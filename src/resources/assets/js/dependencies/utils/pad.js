/**
 * EXPORTS
 * window.APP.padLeft
 * window.APP.padRight
 */

/**
 * Adds leading zeros (or, optionally, something else) to a number
 * 
 * @param string val
 * @param int len
 * @param string filler Defaults to 0
 * @return string The left-padded number
 */
window.APP.padLeft = function(val, len, filler) {
  filler = (typeof filler !== 'undefined') ? filler : '0';
  val += ''; // Cast as string
  while (val.length < len) val = filler + val;
  return val;
};

/**
 * Adds leading zeros (or, optionally, something else) to a number
 * 
 * @param string val
 * @param int len
 * @param string filler Defaults to 0
 * @return string The left-padded number
 */
window.APP.padRight = function (val, len, filler) {
  filler = (typeof filler !== 'undefined') ? filler : '0';
  val += ''; // Cast as string
  while (val.length < len) val = val + filler;
  return val;
};
