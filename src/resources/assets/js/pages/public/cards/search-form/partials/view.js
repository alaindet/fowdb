/**
 * Works best on form/checkboxes, form/checkbox and form/radio components
 * 
 * @param string inputName 
 * @param string|string[] inputValue 
 */
function view_activateButtons(inputName, inputValue) {
  const input = $(`input[name=${inputName}][value="${inputValue}"]`);
  if (!input.length) return;
  const label = input.parents("label");
  if (!label.length) return;
  label.addClass("active");
  input.prop("checked", true);
};
