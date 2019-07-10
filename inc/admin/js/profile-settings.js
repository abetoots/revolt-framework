(function ($) {
    $(document).ready(function () {

        var mediaUploader;

        $('#upload-button').on('click', function (e) {
            e.preventDefault();
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Select a Profile Picture',
                button: {
                    text: 'Select Image'
                },
                multiple: false
            });

            mediaUploader.on('select', function () {
                attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#profile-picture').val(attachment.url);
                $('#profile-picture-preview').attr('src', `${attachment.url}`);
            });

            mediaUploader.open();

        }); //upload button end

        //Remove profile button confirm
        $('#remove-button').on('click', function (e) {
            e.preventDefault();
            let confirmAction = confirm('Are you sure you want to remove the Profile Picture?');
            if (confirmAction == true) {
                $('#profile-picture').val('');
                $('.lofi-profile-form').submit();
            }
            return;
        });//remove button end

    }); //document ready end
})(jQuery);