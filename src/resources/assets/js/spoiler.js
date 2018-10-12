$(document).ready(function () {
    FoWDB.spoiler = {
        /**
         * Returns a string with leading zeros (or custom character) if needed
         *
         * @param integer n Number to process
         * @param integer width Number of characters of output string
         * @param string Custom character instead of 0
         * @return string Padded string
         */
        'pad': function (n, width, z) {
            z = z || '0';
            n = n + '';
            return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
        }
    };

    // Firefox only - hash anchors do not work!
    // Reference here: http://blog.byteb.us/bug-archeology-firefox-hash-navigation/
    //if (location.href.indexOf('#') > -1) { location.href+=''; }


    // Show missing cards -----------------------------------------------------
    $("#opt_i_missing").on("click", function () {

        // Get Show Missing HTML element hook
        var showMissing = $(this);

        // Loop on spoiler sections (one for each spoiler set)
        // To reorder cards by number and THEN show missing ones
        $(".spoiler").each(function () {
            
            var spoilerSection = $(this);
            var spoilerBody = $('.spoiler-body', spoilerSection);
            var setcode = spoilerSection.data('setcode');

            // When deselecting
            if (showMissing.hasClass("active")) {

                // Uncheck input
                $("input", showMissing).removeAttr("checked");

                // Delete every missing card
                $(".fdb-card-missing").remove();

                // Re-sort cards by ID (spoiled order) and append to viewer
                $('.fdb-card', spoilerSection).sort(function(a,b) {

                    // Get card codes
                    var a_id = parseInt($('img', a).data('id')),
                        b_id = parseInt($('img', b).data('id'));

                    // Sorting criteria
                    if (a_id < b_id) { return 1; }
                    else if (a_id > b_id) { return -1; }
                    else { return 0; }

                }).appendTo(spoilerSection);

                // Fit cards to screen
                FoWDB.search.fitCards();
            }
            
            // When selecting
            else {

                $("input", showMissing).attr("checked", "true"); // Check the clicked input

                var nums = []; // This will hold spoiled card numbers

                // Loop on each card of this spoiler to get all spoiled card numbers
                $('.fdb-card', spoilerSection).each(function () {

                    var num = $('img', $(this)).data('num'); // Get card number

                    // Save card number into nums array if no other card has the same card num
                    // This is needed instead of simply nums.push(num)
                    // because Rulers/J-rulers and shift cards have the same num!
                    if($.inArray(num, nums) < 0) { nums.push(parseInt(num)); }
                });

                nums.sort(function (a,b) { return a - b; }); // Sort array numerically

                // Check card numbers
                // (Loop as many times as biggest card number to fill missing card numbers)
                var j = 1,
                    missing = [],
                    len = parseInt(spoilerSection.data('setcount'));

                for (var i = 0; i < len; i++) {
                    
                    if (j != nums[i]) {
                        nums.splice(i, 0, j);
                        missing.push(j);
                    }

                    j++;
                }

                // Add covered cards to viewer
                for (var k = 0, len = missing.length; k < len; k++) {

                    // Create card container
                    $("<div>")
                        .addClass("fdb-card")
                        .addClass("fdb-card-missing")
                        // Append card number label
                        .append($("<span class='missing-label'>").text(missing[k]))
                        // Append missing card image (card back)
                        .append(
                            $("<img>")
                                .attr('src', '_images/in_pages/search/more.jpg')
                                .attr('alt', 'Missing card - ' + FoWDB.spoiler.pad(missing[k], 3))
                                .attr(
                                    'data-code',
                                    (setcode + '-' + FoWDB.spoiler.pad(missing[k], 3)).toUpperCase()
                                )
                                .attr('data-num', missing[k])
                        )
                        // Prepend missing card to viewer
                        .appendTo(spoilerBody);
                }

                // Sort cards with card code and append them to viewer
                $('.fdb-card', spoilerSection).sort(function(a,b) {

                    // Get card codes
                    var a_code = $('img', a).data('code');
                    var b_code = $('img', b).data('code');

                    // Sorting criteria
                    if (a_code < b_code) { return -1; }
                    else if (a_code > b_code) { return 1; }
                    else { return 0; }

                }).appendTo(spoilerBody);

                // Fit cards
                FoWDB.search.fitCards();
            }
        });
    });

    if (window.innerWidth > 768) {
        $(".js-hider[data-target='#hide-options']").click();
    }
});
