/**
 * IMPORTS
 * dependencies/utils/arg
 * 
 * EXPORTS
 * window.APP.clearAlerts
 * window.APP.alert
 */

/**
 * Clear all existing alerts
 *
 * @return this Chainable method
 */
window.APP.clearAlerts = () => {
  $('.fd-alerts').empty();
  return this;
};

/**
 * Adds an alert dynamically
 * 
 * @param string message The alert message
 * @param string type The alert type (Bootstrap 3)
 * @param bool returnHtml If TRUE, returns the message instead of appending it
 * @return this
 */
window.APP.alert = (message, type, returnHtml) => {

  message = window.APP.arg(message, '');
  const types = ['succes', 'info', 'warning', 'danger'];
  type = (types.indexOf(type) !== -1) ? type : 'info';
  returnHtml = window.APP.arg(returnHtml, false);

  const html = (
    '<div class="fd-alert alert alert-'+type+' alert-dismissable">'+
      '<a href="#" class="close" data-dismiss="alert" aria-label="close">'+
        '&times;'+
        '</a>'+
      '<div class="fd-alert-content">'+message+'</div>'+
    '</div>'
  );

  if (returnHtml) {
    return html;
  }

  const alerts = $('.fd-alerts');
  alerts.first().append(html);
  $('html').animate(
    {
      scrollTop: alerts.offset().top
    },
    200
  );

  return this;

};
