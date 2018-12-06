(function () {

    var css_chapter = '.fd-cr-bdy__1';
    var css_highlight = '.fd-cr-highlight';
    var css_highlight_class = css_highlight.substr(1);

    function selectItem() {

        // Deselect previously selected item
        deselectItem();

        // Read current hash
        var hash = window.location.hash;

        // Highlight stuff
        if (hash && hash.substr(-2) !== '00') {
            $('a[href="' + hash + '"]').closest('li').addClass(css_highlight_class);
        }

    }

    function deselectItem() {
        $([css_chapter, css_highlight].join(' ')).removeClass('highlight');
    }

    $(document).ready(selectItem);
    $(window).on('hashchange', selectItem);

})();
