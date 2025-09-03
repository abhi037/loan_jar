$(function () {
  // Initialize the form wizard
  var form = $("#wizard_with_validation").show();
  
  form.steps({
    headerTag: "h3",
    bodyTag: "fieldset",
    transitionEffect: "slideLeft",
    onInit: function (event, currentIndex) {
      setButtonWavesEffect(event);
    },
    onStepChanging: function (event, currentIndex, newIndex) {
      if (currentIndex > newIndex) {
        return true;
      }

      if (currentIndex < newIndex) {
        form.find(".body:eq(" + newIndex + ") label.error").remove();
        form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
      }

      form.validate().settings.ignore = ":disabled,:hidden";
      return form.valid();
    },
    onStepChanged: function (event, currentIndex, priorIndex) {
      setButtonWavesEffect(event);
    },
    onFinishing: function (event, currentIndex) {
      form.validate().settings.ignore = ":disabled";
      return form.valid();
    },
    onFinished: function (event, currentIndex) {
      // Here, we use a standard form submission
      // Since you want a normal form submit, the form will submit automatically
      form.submit(); // This triggers a standard form submit
    },
  });

  form.validate({
    highlight: function (input) {
      $(input).parents(".form-line").addClass("error");
    },
    unhighlight: function (input) {
      $(input).parents(".form-line").removeClass("error");
    },
    errorPlacement: function (error, element) {
      $(element).parents(".form-group").append(error);
    },
    rules: {
      confirm: {
        equalTo: "#password",
      },
    },
  });
});

function setButtonWavesEffect(event) {
  $(event.currentTarget).find('[role="menu"] li a').removeClass("waves-effect");
  $(event.currentTarget)
    .find('[role="menu"] li:not(.disabled) a')
    .addClass("waves-effect");
}
