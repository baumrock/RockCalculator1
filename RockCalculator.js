$(document).ready(function() {
  const tooltip = new Tooltip();
  let timeout;
  let lastinput;

  // get calc result for given input
  let getResult = function(input) {
    let $input = $(input);
    // replace german comma by english dot (which has to be used for storage)
    let str = $input.val().replace(/,/g,'.');
    try {
      if(!str) return tooltip.hide();
      result = math.format(math.eval(str), {precision: 14});
      $input.removeClass('RockCalculatorInvalid');
      if(result && !$.isNumeric(result)) throw error;
    } catch (error) {
      result = $input.val();
      $input.addClass('RockCalculatorInvalid');
      valid = false;
    }

    // round numeric results
    if($.isNumeric(result)) {
      let precision = $input.data('rockcalculator');
      let round = math.round(result, precision);
      result = round.toFixed(precision);
    }

    return result;
  }

  let setResult = function(input) {
    let $input = $(input);
    $input.val(getResult(input));
  }

  function calc(event) {
    let input = event.target;

    // check if we jumped to a new input
    // in that case we immediately fire the calc on the old input
    if(input !== lastinput && lastinput) setResult(lastinput);
    lastinput = input;

    // show tooltip (debounced)
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      let result = getResult(input);

      // hide or show tooltip
      if(!result) tooltip.hide();
      else {
        if(event.type == 'focusout') tooltip.hide();
        else tooltip.show(input, result, {placement:'top'});
      }

      // if the event was triggered by a change or focusout
      // we update the input value
      if(event.type == 'change' || event.type == 'focusout') setResult(input);
    }, 300);
  }

  // listen to events and fire callback
  $(document).on('keyup focus change focusout', '[data-rockcalculator]', calc);

  // set all results of calculator fields before a form is submitted
  $(document).on('submit', 'form', function(e) {
    let form = e.target;
    let $calcFields = $(form).find("input[data-rockcalculator]");
    $.each($calcFields, function(i, $field) {
      setResult($field);
    });
  });
});
