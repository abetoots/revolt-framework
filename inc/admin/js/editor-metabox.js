(function ($) {
    $(document).ready(function () {
        //Check initial states of doe checkbox
        if ($('#lofi-job-post-doe-field').is(':not(:checked)')) {
            $('#lofi-job-post-salary-field').prop('required', true);
        } else if ($('#lofi-job-post-doe-field').is(':checked')) {
            $('#lofi-salary-wrap').addClass("hidden");
        }
        //Dynamically hide the salary field based on doe checkbox
        $('#lofi-job-post-doe-field').on('click', function () {
            if ($(this).prop('checked')) {
                $('#lofi-salary-wrap').fadeOut()
                $('#lofi-job-post-salary-field').removeAttr('required');
            } else if ($(this).prop('checked') == false) {
                $('#lofi-salary-wrap').removeClass("hidden");
                $('#lofi-salary-wrap').fadeIn();
                $('#lofi-job-post-salary-field').prop('required', true);
            }
        });



        $('#publish').on('click', function (e) {
            let val1 = $('#lofi-job-post-salary-field').val();
            let val2 = $('#lofi-job-post-salary-field-optional').val();
            //if value of field 1 is 
            if (val1 > val2 && val2) {
                alert('First value cannot be higher than second value!');
                e.preventDefault();
            }
        });
    }); //document ready end
})(jQuery);