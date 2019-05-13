(function () {

    const css_chapter = '.fd-cr-bdy__1';
    const css_highlight = '.fd-cr-highlight';
    const css_highlight_class = css_highlight.substr(1);

    const selectItem = () => {

        // Deselect previously selected item
        deselectItem();

        // Read current hash
        const hash = window.location.hash;

        // Highlight stuff
        if (hash && hash.substr(-2) !== '00') {
            $('a[href="' + hash + '"]')
                .closest('li')
                .addClass(css_highlight_class);
        }

    }

    const deselectItem = () => {
        $([css_chapter, css_highlight].join(' '))
            .removeClass(css_highlight_class);
    }

    $(document).ready(selectItem);
    $(window).on('hashchange', selectItem);

})();
