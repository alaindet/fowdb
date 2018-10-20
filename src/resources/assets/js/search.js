$(document).ready(function() {
    FoWDB.search = {
        // Pagination number counter (used by Load More)
        page: 1,
        
        // Cards counter from HTML
        cardsCounter: parseInt($("#cards-counter").html()),

        /**
         * Checks if an input is an integer
         * @param  {any} n
         * @return {integer} Value of input as integer or 0 on error
         */
        'isInteger': function (n) {
            return Number(parseInt(n)) === parseInt(n) && parseInt(n) % 1 === 0 ? parseInt(n) : 0;
        },

        /**
         * Fit cards to the screen size
         * 
         * @param  {boolean} $ini Flag for initializing (skip input value)
         */
        'fitCards': function ($ini) {
            
            // Get name of the cards container element
            var name = "cards-container",
                // Check if id (search page) or class (spoiler page, multiple results panels)
                container = $("#"+name).length ? $("#"+name) : $("."+name),
                // Get width of Results panel
                cardsContainerWidth = parseInt(container.width()),
                // Get user input element
                input = $("#opt_i_numxrow"),
                // Default horizontal size for readable cards, in pixel
                defaultWidth = 200;

            if ($ini) { // Skip reading input value on first run

                // Calculate value based on viewport
                var cardsPerRow = Math.floor(cardsContainerWidth/defaultWidth);

            } else { // Read input value instead

                // Get current input value
                var cardsPerRow = this.isInteger(input.val());

                // Validate input value (must be integer from 1 to 10 included)
                if (cardsPerRow < 1) {cardsPerRow = 1;}
                if (cardsPerRow > 10) {cardsPerRow = 10;}
            }

            // Update displayed input value
            input.val(cardsPerRow);

            // Replace every possible CSS classes with just the right one
            // (Ex.: mog-card-3 means cards have width 33.33% so 3 per row show)
            $(".fdb-card")
                .removeClass(
                    "fdb-card-1 fdb-card-2 fdb-card-3 fdb-card-4 fdb-card-5 " +
                    "fdb-card-6 fdb-card-7 fdb-card-8 fdb-card-9 fdb-card-10 "
                )
                .addClass('fdb-card-'+cardsPerRow);
        }
    };

    // Turn Set input into multiple
    $("#setcode-multiple").on("click", function() {
        var t = $("#setcode");
        t.attr("multiple") ? (t.removeAttr("multiple").removeAttr("size"), t.attr("name", "setcode")) : (t.attr("multiple", "true").attr("size", 10), t.attr("name", "setcode[]"))
    });

    // Update ATK opearator when selecting from dropdown
    $(".seldrop_atk-operator").each(function() {
        $(this).on("click", function() {
            var t = $(this).data("words"),
                a = $(this).data("symbol"),
                e = $(".dropdown-face", "#atk-group");
            e.html(a), $("#atk-operator").val(t)
        })
    });

    // Update DEF opearator when selecting from dropdown
    $(".seldrop_def-operator").each(function() {
        $(this).on("click", function() {
            var t = $(this).data("words"),
                a = $(this).data("symbol"),
                e = $(".dropdown-face", "#def-group");
            e.html(a), $("#def-operator").val(t)
        })
    });

    // Update sort field when selecting from dropdown
    $(".seldrop_sort").each(function() {
        $(this).on("click", function() {
            var t = $(this).data("field"),
                a = $(this).data("field-name"),
                e = $(".dropdown-face", "#sort-group");
            e.html(a), $("#sort").val(t)
        })
    });

    // Update sort direction
    $("#sortdir_handle").on("click", function() {
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $("#sortdir").val("asc");
        } else {
            $(this).addClass("active");
            $("#sortdir").val("desc");
        }
    });

    // Submit the form
    $("#form_search").on("submit", function() {
        if (!$("#q").val()) {
            $("#q").prop("disabled", !0);
        }

        if ("" == $("#atk").val()) {
            $("#atk").prop("disabled", !0);
            $("#atk-operator").prop("disabled", !0);
        }

        if ("" == $("#def").val()) {
            $("#def").prop("disabled", !0);
            $("#def-operator").prop("disabled", !0);
        }

        var t = $("#setcode :selected"),
            a = t.first();

        $("#setcode").attr("multiple") ? 1 == t.length && "0" == a.val() && a.prop("disabled", !0) : "0" == a.val() && a.prop("disabled", !0)
        $("input[name='race']").val() || $("input[name='race']").prop("disabled", !0);
        $("input[name='artist']").val() || $("input[name='artist']").prop("disabled", !0);

        if ("default" == $("#sort").val()) {
            $("#sort").prop("disabled", !0);
        }

        if ("asc" == $("#sortdir").val()) {
            $("#sortdir").prop("disabled", !0);
        }
    });

    // Reset the form
    $(".form-reset").on("click", function() {
        $("label.btn", "#form_search").removeClass("active");
        $("input", "#form_search label.btn").removeAttr("checked");
        $("input[name='format']").each(function() {
            "wandr" == $(this).val() && ($(this).closest("label").addClass("active"), $(this).prop("checked", !0))
        });
        $("input[name='exact']").first().prop("checked", !0);
        $("input[name='exact']").first().closest("label").addClass("active");
        if ($("#setcode").attr("multiple")) {
            $("#setcode").removeAttr("multiple").removeAttr("size");
            $("#setcode").attr("name", "setcode")
        }
        $("#setcode :selected").removeAttr("selected");
        $('input[type="text"]', "#form_search").val("");
        $("input#atk-operator").val("equals");
        $(".dropdown-face", "#atk-group").html("=");
        $("input#def-operator").val("equals");
        $(".dropdown-face", "#def-group").html("=");
        $("input#sort").val("sets_id");
        $(".dropdown-face", "#sort-group").html("Choose");
        $("input#sortdir").val("asc");
        $("#sortdir_handle").removeClass("active");
    });

    // Cards per row: minus button
    $("#opt_b_numxrow_minus").on("click", function() {
        var t = parseInt($("#opt_i_numxrow").val()) - 1;
        t > 0 ? $("#opt_i_numxrow").val(t) : $("#opt_i_numxrow").val(1), FoWDB.search.fitCards()
    });

    // Cards per row: plus button
    $("#opt_b_numxrow_plus").on("click", function() {
        var t = $("#opt_i_numxrow").val();
        $("#opt_i_numxrow").val(parseInt(t) + 1), FoWDB.search.fitCards()
    });

    // Trigger cards per row on Enter inside input
    $("#opt_i_numxrow").keyup(function(t) {
        13 == t.keyCode && FoWDB.search.fitCards()
    });

    // Backside clear
    $("#backside-clear").on("click", function () {
        $("label", $(this).parents(".filter")).each(function () {
            $(this).removeClass("active");
            $("input", $(this)).attr("checked", false);
        });
    });

    // Trigger on form submit (button click on Load More)
    $("#loadCards").on("submit", function (e) {

        // Prevent submitting
        e.preventDefault();

        // Save reference
        var thisElement = $(this);

        // Increment pagination counter
        FoWDB.search.page++;

        // Get search filters from GET params and add page number
        var filters = window.location.search.replace("?", "")
                    + "&page="  + FoWDB.search.page
                    + "&token=" + $('input[name=token]').val();

        $.ajax({
            url: '/api/search/load.php',
            method: 'POST',
            data: filters,
            dataType: 'json',
            cache: false,
            error: function (jqxhr, textStatus, errorThrown) {
                console.log('ERROR!');
                console.log('jqxhr', jqxhr);
                console.log('textStatus', textStatus);
                console.log('errorThrown', errorThrown);
            },
            success: function (data, message) {

                // Generate card class based on current cards-per-row number
                var cardClass = "fdb-card fdb-card-" + $("#opt_i_numxrow").val(),
                    // Will hold every card's HTML as string into its elements
                    cardsHTML = [],
                    // Initialize output string
                    output = '';

                // Loop on data to generate HTML
                for (var i = 0, len = data.cardsData.length; i < len; i++) {

                    var c = data.cardsData[i]; // Current card's data
                    var spoilerClass = c.isspoiler ? " fdb-card-spoiled" : ""; // Spoiler?

                    // Card HTML content
                    cardsHTML.push("<div class=\"" + cardClass + spoilerClass + "\"><a href='/?p=card&code=" + c.code + "' target='_self'><img src=\"" + c.thumb_path + "\" data-code=\"" + c.code + "\" data-id=\"" + c.id + "\" data-set=\"" + c.setcode + "\" alt=\"" + c.name + "\"></a></div>");
                }

                // Get output as string
                var output = cardsHTML.join('');

                // Append card to cards container and animate it
                $(output).insertBefore(".fdb-card-load").hide().show("fast");

                // Update "Results" panel title
                FoWDB.search.cardsCounter += data.cardsData.length;
                $("#cards-counter").html(FoWDB.search.cardsCounter);

                // Check if no more pagination is needed
                if (!data.nextPagination) {

                    // Replace Load More button with an horizontal line
                    thisElement.remove();
                }
            }
        });
    });

    // "Activate" buttons when showing panels
    $(".js-panel-toggle").on("click", function () {
        $(this).toggleClass("active btn-default fdb-btn");
    });

    // Put Options and Results panels side by side on desktops
    $("#js-panel-toggle-options").on("click", function () {
        if (window.innerWidth > 768) {
            $("#hide-options").toggleClass("col-sm-3");
            $("#search-results").toggleClass("col-sm-9");
        }
    });

    // DEFAULTS ---------------------------------------------------------------

    // Fit cards to screen when showing results
    if ($("#search-results").length > 0) {
        FoWDB.search.fitCards(true);
    }

    // Show/Hide loading icon when loading
    $(document).on({
        ajaxStart: function() { $(".fdb-loading-icon .fa").addClass("fa-spin"); },
        ajaxStop: function() { $(".fdb-loading-icon .fa").removeClass("fa-spin"); }    
    });

    // Acquire page number from input
    if ($("input[name=page]").length) {
        FoWDB.search.page = parseInt($("input[name=page]").val());
    }
});
