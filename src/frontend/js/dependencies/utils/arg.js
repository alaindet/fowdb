/**
 * EXPORTS
 * window.APP.arg
 */

/**
 * Returns passed argument or a default value, if provided
 * 
 * @param <T>
 * @return <T>
 */
window.APP.arg = (x, defaultValue) => {
  return (typeof x !== 'undefined') ? x : defaultValue;
};
