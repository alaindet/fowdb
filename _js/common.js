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
      r = "<div class='row notification'><div class='col-xs-12'><div class='alert alert-" + a + "''><span class='notif-remove pointer'>&times;</span><span class='notif-content'> " + t + "</span></div></div></div>";
    return !!t.length && (l || ($("#header").append(r), $("html, body").animate({
        scrollTop: $(".notification").offset().top
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

  // Remove notification ----------------------------------------------------
  $("body").on("click", ".notif-remove", function() {
    $(this).parents(".notification").remove()
  });

  // Save previous page when clicking Admin on menu -------------------------
  $(".menu-admin").on("click", function() {
    $.post("/api/admin/previous.php",
      { previous: window.location.href },
      function (data) {
        window.location.href = window.location.protocol+"//"+window.location.host+"/?p=admin";
      },
      "json"
    );
  });
});
