(function () {

  // CSS Selectors ------------------------------------------------------------
  var css_helloWorld = '.hello-world';

  
  // Application data ---------------------------------------------------------
  var data_message = 'Hello World!';


  // Bootstrap ----------------------------------------------------------------
  function bootstrap() {

    $(document)

      // Original events
      .on('click', css_helloWorld, function() {
        handleHelloWorldClick($(this));
      })

      // Custom events
      .on('fd:hello-world', handleHelloWorldEvent)

  }


  // Controller functions -----------------------------------------------------

  function handleHelloWorldClick(target) {
    $(document).trigger('fd:hello-world', [a, b, c]);
  }

  function handleHelloWorldEvent(event, a, b, c) {
    console.log(data_message);
  }


  // Model functions ----------------------------------------------------------

  // ...


  // View functions -----------------------------------------------------------

  // ...


  // Utility functions --------------------------------------------------------

  // ...


  // Go! ----------------------------------------------------------------------
  $(document).ready(bootstrap);

})();
