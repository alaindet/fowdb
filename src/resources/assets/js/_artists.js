$(document).ready(function () {
  var input = $("#the-autocomplete");
  input.autocomplete({
    source: "/old/api/api.autocomplete.artists.php",
    select: function (event, ui) {
      event.preventDefault();
      input.val(ui.item.label);
    },
    delay: 300,
    minLength: 2
  });
});
