$(document).ready(function() {

    var app = {
        el: {
            cardName: $('#card-name-autocomplete'),
            cardId: $('#card-id'),
            cardImage: $('#card-image'),
            form: $('#validate-this-form'),
        },
        url: {
            autocomplete: '/old/api/autocomplete-name-label.php',
            check: '/old/api/check-card-id.php'
        }
    };

    // Autocomplete card name and code on input
    app.el.cardName.autocomplete({
        source: app.url.autocomplete,
        select: function(event, ui) {
            event.preventDefault();
            app.el.cardName.val(ui.item.label);
            app.el.cardId.val(ui.item.id);
            app.el.cardImage.html([
              '<a ',
                'href="',ui.item.image,'" ',
                'data-lightbox="cards" ',
                'data-title="',ui.item.label,'"',
              '>',
                '<span class="fd-zoomable-lg">',
                  '<img ',
                    'src="',ui.item.image,'" ',
                    'class="img-responsive" ',
                    'width="200px"',
                  '>',
                '</span>',
              '</a>',
              '<br>',
                '<a ',
                  'href="',ui.item.link,'" ',
                  'class="btn btn-link"',
                '>',
                  '<i class="fa fa-external-link"></i> ',
                  'Go to card page',
              '</a>'
            ].join(''));
        },
        delay: 300,
        minLength: 2
    });

    // Validate the form before submitting
    app.el.form.on('submit', function (event) {
        event.preventDefault();
        var id = { id: app.el.cardId.val() };
        $.getJSON(app.url.check, id, function (data) {
            if (data.response) app.el.form.unbind().submit();
            else FoWDB.notify(data.message, 'danger');
        });
    });

    // Show X button to clear the input
    $('.has-clear input[type="text"]')
      .on('input propertychange', function () {
        var visible = Boolean($(this).val());
        $(this).siblings('.form-control-clear').toggleClass('hidden', !visible);
      })
      .trigger('propertychange');

    // Clear the input when clicking the X button
    $('.form-control-clear')
      .click(function () {
        app.el.cardName.val('').focus();
        app.el.cardId.val('');
        app.el.cardImage.html('Image will be shown here');
        $(this).siblings('input[type="text"]').val('');
        $(this).toggleClass('hidden');
      })
      .trigger('propertychange').focus();

});
