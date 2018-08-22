window.FoWDB.cr = {};
window.FoWDB.cr.unselect = function() {
    $(".cr .highlight").removeClass("highlight");
}
window.FoWDB.cr.select = function() {
    window.FoWDB.cr.unselect();
    var hash = window.location.hash;
    if (hash) {
        if (hash.substr(-2) !== "00") {
            $("a[href='"+hash+"']").closest("li").addClass("highlight");
        }
    }
};

// Trigger the highlighting on document load and on hash change
$(document).ready(window.FoWDB.cr.select);
$(window).on("hashchange", window.FoWDB.cr.select);
