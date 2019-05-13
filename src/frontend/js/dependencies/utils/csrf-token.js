/**
 * EXPORTS
 * window.APP.readAntiCsrfToken
 */

/**
 * Reads the anti-CSRF token
 * 
 * @return string
 */
window.APP.readAntiCsrfToken = () => {
  return $('meta[name="_token"]').attr('content');
};
