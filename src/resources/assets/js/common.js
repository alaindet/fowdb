// Library ----------------------------------------------------------------
window.FoWDB = {
  getBaseUrl: function(n) {
    var i = "object" == typeof n ? n : {},
      o = !1,
      t = window.location.protocol,
      e = window.location.hostname,
      a = window.location.pathname,
      l = window.location.search,
      r = window.location.hash,
      s = t + "//" + e;
    if (i.page)
      if (o) s += a;
      else
        for (var c = l.replace("?", "").split("&"), d = 0, h = c.length; d < h; d++) {
          var f = c[d];
          if (f.indexOf("p=") > -1) {
            s += "/?" + f;
            break
          }
        }
      return i.hash && (s += r), s
  },
  notify: function(n, i, o) {
    var t = n ? n : "",
      e = ["success", "info", "warning", "danger"],
      a = e.indexOf(i) > 0 ? i : "info",
      l = !!o && o,

      r = [
        '<div class="fd-alert alert alert-',a,' alert-dismissable">',
          '<a href="#" class="close" data-dismiss="alert" aria-label="close">',
            '&times;',
          '</a>',
          '<div class="fd-alert-content">',t,'</div>',
        '</div>',
      ].join('');


    return !!t.length && (l || ($(".fd-alerts").first().append(r), $("html, body").animate({
        scrollTop: $(".fd-alerts").offset().top
    }, 200)), !l || r)
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

  // LEGACY CODE
  // Hide content -----------------------------------------------------------
  $(".hide-handle").on("click", function() {
    var n = $(this).closest(".to-hide"),
      i = ($(this), $(".hide-content", n));
    i.toggleClass("hidden")
  });
});
