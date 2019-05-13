/**
 * Reads the anti-CSRF token from a <meta> tag into <head>
 */
window.APP.readAntiCsrfToken = () => {
  return $('meta[name="_token"]').attr('content');
};
