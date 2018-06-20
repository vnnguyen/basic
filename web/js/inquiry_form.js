/* ------------------------------------------------------------------------------
*
*  # Form wizard
*
*  Specific JS code additions for wizard_form.html page
*
*  Version: 1.1
*  Latest update: Dec 25, 2015
*
* ---------------------------------------------------------------------------- */

$(function() {
    // Initialize wizard
    var FORM = $(".form-add-steps");
    FORM.formwizard({
        disableUIStyles: true,
        disableInputFields: false,
        inDuration: 150,
        outDuration: 150,
        focusFirstInput : true,
        // formPluginEnabled: true,
        // formOptions :{
        //     beforeSubmit: function(arr, form, options) { //console.log(1);
        //         // The array of form data takes the following form: 
        //         // [ { name: 'username', value: 'jresig' }, { name: 'password', value: 'secret' } ] 
        //         // console.log(arr);console.log(options);return false;
        //          form.submit();

        //         // return false to cancel submit                  
        //     }
        // }
    });
    $(document).on('click', "#add-step", function(){
        var fieldset = $(".extra-steps .step:first");
        $(".step-wrapper").append($(fieldset));
        $(FORM).formwizard("update_steps");
        if ($(FORM).formwizard('state').steps.length == 1) {
            $(FORM).formwizard('state').nextButton.text('Submit');
        } else {
            $(FORM).formwizard('state').nextButton.text('Next');
        }
    });
    $(FORM).on("step_shown", function(event, data){
        if (data.isLastStep) {
            $(data.nextButton).text('Submit');
        } else {
            $(data.nextButton).text('Next');
        }
    });
    if ($(FORM).formwizard('state').steps.length == 1) {
        $(FORM).formwizard('state').nextButton.text('Submit');
    } else {
        $(FORM).formwizard('state').nextButton.text('Next');
    }
});
