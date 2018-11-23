// Library ----------------------------------------------------------------
window.FoWDB = {
  clearNotifications: function () {
    $('.fd-alerts').empty();
    return this;
  },
  notify: function(message, type, returnHtml) {
    var message = message || '';
    var types = ['success', 'info', 'warning', 'danger'];
    var type = types.indexOf(type) !== -1 ? type : 'info';
    var returnHtml = returnHtml || false;

    var html = [
      '<div class="fd-alert alert alert-',type,' alert-dismissable">',
        '<a href="#" class="close" data-dismiss="alert" aria-label="close">',
          '&times;',
        '</a>',
        '<div class="fd-alert-content">',message,'</div>',
      '</div>',
    ].join('');

    if (returnHtml) return html;

    var alerts = $('.fd-alerts');
    alerts.first().append(html);
    $('html').animate({ scrollTop: alerts.offset().top }, 200);
  }
};

$(document).ready(function() {
  
  // Hide and show elements
  $(".js-hider").on("click", function () {

    // Get opening and closing icons, if any
    var openIcon = $(this).data("open-icon") || "fa-chevron-down";
    var closedIcon = $(this).data("closed-icon") || "fa-chevron-right";

    // Change this button's icon if it has it
    $("i.fa", $(this))
      .toggleClass(openIcon+' '+closedIcon);

    // Toggle target element's visibility
    $($(this).data("target")).toggleClass("hidden");
  });

  // LEGACY CODE
  // Hider component (new) ---------------------------------------------------
  $(".hider").on("click", function () {
    $($(this).data("target"))
      .toggleClass("hidden");
  });
});
