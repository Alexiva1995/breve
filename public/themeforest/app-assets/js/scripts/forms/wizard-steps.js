/*=========================================================================================
    File Name: wizard-steps.js
    Description: wizard steps page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

// Wizard tabs with numbers setup
$(".number-tab-steps").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onFinished: function(event, currentIndex) {
        alert("Form submitted.");
    }
});

// Wizard tabs with icons setup
$(".icons-tab-steps").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onFinished: function(event, currentIndex) {
        alert("Form submitted.");
    }
});

// Validate steps wizard

// Show form
var form = $(".steps-validation").show();

$(".steps-validation").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Enviar'
    },
    onStepChanging: function(event, currentIndex, newIndex) {
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex) {
            return true;
        }

        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex) {
            // To remove error styles
            form.find(".body:eq(" + newIndex + ") label.error").remove();
            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onFinishing: function(event, currentIndex) {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function(event, currentIndex) {
        // alert("Submitted!");
        var contador = 0;
        var equipos = document.getElementsByClassName("equipment-checkbox");
        for (i = 0; i < equipos.length; i++) {
            if (equipos[i].checked) {
                contador++;
            }
        }

        if (document.getElementById("type").value == 'Programado'){
            if (document.getElementById("time-error-check").value == 0){
                if (contador > 0) {
                    $('#form-validation').submit();
                } else {
                    alert("Debe seleccionar un tipo de equipamiento");
                    return false;
                }
            }else{
                alert("Por favor, ingrese una hora válida. (De 7:00 a 18:00)");
                return false;
            }
        }else{
            if (contador > 0) {
                $('#form-validation').submit();
            } else {
                alert("Debe seleccionar un tipo de equipamiento");
                return false;
            }
        }
    }
});

// Initialize validation
$(".steps-validation").validate({
    ignore: 'input[type=hidden]', // ignore hidden fields
    errorClass: 'danger',
    successClass: 'success',
    highlight: function(element, errorClass) {
        $(element).removeClass(errorClass);
    },
    unhighlight: function(element, errorClass) {
        $(element).removeClass(errorClass);
    },
    errorPlacement: function(error, element) {
        error.insertAfter(element);
    },
    rules: {
        email: {
            email: true
        }
    }
});