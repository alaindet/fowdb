/**
 * EXPORTS
 * window.APP.clearAlerts
 * window.APP.alert
 */

/**
 * Clear all existing alerts
 *
 * @return this
 */
window.APP.clearAlerts = function () {

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
window.APP.alert = function(message, type, returnHtml) {

  var message = message || '';
  var types = ['succes','info','warning','danger'];
  var type = types.indexOf(type) !== -1 ? type : 'info';
  var returnHtml = returnHtml || false;

  var html = (
    '<div class="fd-alert alert alert-'+type+' alert-dismissable">'+
      '<a href="#" class="close" data-dismiss="alert" aria-label="close">'+
        '&times;'+
        '</a>'+
      '<div class="fd-alert-content">'+message+'</div>'+
    '</div>'
  );

  if (returnHtml) return html;

  var alerts = $('.fd-alerts');
  alerts.first().append(html);
  $('html').animate({ scrollTop: alerts.offset().top }, 200);

  return this;

};
