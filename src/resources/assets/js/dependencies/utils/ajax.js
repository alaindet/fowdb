/**
 * IMPORTS
 * dependencies/utils/csrf-token.js
 * 
 * EXPORTS
 * window.APP.ajax
 */

/**
 * Performs an AJAX request to an endpoint.
 * Integrates the anti-CSRF token on POST requests, read from its <meta> tag
 * 
 * Please note the onSuccess, onError and onComplete callbacks are called
 * inside the 'success' jQuery option anyway and thus imply the server
 * responded with a proper JSON anyway. This allows the UI to show soft errors
 * like validation errors, existing resources etc.
 * 
 * "Real" AJAX errors like invalid JSON or server not responding are simply
 * output into the console via the 'error' jQuery option
 * 
 * @param args.url string
 * @param args.data object
 * @param args.type string
 * @param args.onSuccess callback
 * @param args.onError callback
 * @param args.onComplete callback
 */
window.APP.ajax = (args) => {

  const ajax = {};

  // Default data and request type
  if (typeof args.data === 'undefined') args.data = {};
  if (typeof args.type === 'undefined') args.type = 'get';

  // Set Anti-CSRF token for any non-GET request
  if (args.type !== 'get') {
    const token = window.APP.readAntiCsrfToken();
    args.data._token = token;
    ajax.headers = { 'X-CSRF-TOKEN': token };
  }

  // Default error handler
  if (typeof args.onError === 'undefined') {
    args.onError = function (response) {
      console.log(response.message);
    };
  }

  // Default complete handler (executes anyway, after onSuccess/onError)
  if (typeof args.onComplete === 'undefined') {
    args.onComplete = function () {};
  }

  // Define the props of the ajax object
  ajax.url = args.url;
  ajax.type = args.type.toUpperCase();
  ajax.dataType = 'json';
  ajax.data = args.data;
  ajax.success = (response) => {
    if (!response.error) {
      args.onSuccess(response);
    } else {
      args.onError(response);
    }
    args.onComplete();
  };
  ajax.error = (xhr, message, error) => {
    console.log(xhr.responseText, message, error)
  };

  // Call the API
  jQuery.ajax(ajax);

};
