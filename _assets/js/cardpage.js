$(document).ready(function() {

    // Trigger on page load
    highlightRuling(window.location.hash);

    // Trigger on every hash change
    window.onhashchange = function (e) {
        highlightRuling(e.newURL.substring(e.newURL.indexOf("#")));
        prompt("Copy this link to share the ruling", window.location.href);
    }

    function highlightRuling(hash) {
        if (hash.substring(0,1) == "#") {
            hash = hash.substring(1);
            $(".f-rulings-highlight").removeClass("f-rulings-highlight");
            $("a[name="+hash+"]").parents(".f-rulings-item").addClass("f-rulings-highlight");    
        }
    }
});
