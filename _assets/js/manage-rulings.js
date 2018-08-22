$(document).ready(function() {

    var nameInput = $("#name_suggest"),
        codeInput = $("#code"),
        idInput = $("#card_id"),
        imageBox = $("#card_image"),
        form = $("#ruling-form"),
        action = $("#action").val();

    if (action == "create") {

        // Autocomplete on name input
        nameInput.autocomplete({
            source: "/api/api.autocomplete.name2code.php",
            select: function(event, ui) {

                // Prevent filling the input with card code
                event.preventDefault();

                // Update inputs
                nameInput.val(ui.item.label); // Name input
                codeInput.val(ui.item.value); // Code input
                idInput.val(ui.item.id);     // Card ID hidden input

                // Add image
                imageBox.html(
                    '<div class="col-sm-offset-2 col-sm-10">'
                    +'<img src="'+ui.item.path+'" alt="'+ui.item.label+'('+ui.item.value+')">'
                    +'</div>'
                );
            },
            delay: 300,
            minLength: 2
        });

        // Validate the form
        form.on("submit", function (event) {
            event.preventDefault();
            $.getJSON("/api/api.checkCard.php",
                {
                    name: nameInput.val(),
                    code: codeInput.val()
                },
                function(data) {
                    if (data.response) {
                        form[0].submit();
                    }
                }
            );
        });
    }
});
