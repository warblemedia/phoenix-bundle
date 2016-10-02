(function ($, Phoenix) {
    var input = $('#profile_photo_input');
    var preview = $('#profile_photo_preview');

    input.on('change', function () {
        // Get selected file
        var file = input[0].files[0];

        // Update preview
        var previewReader = new FileReader();
        previewReader.onload = function (e) {
            preview.attr('src', e.target.result);
        };
        previewReader.readAsDataURL(file);

        // Upload file to the server
        var formData = new FormData();
        formData.append(Phoenix.uploadKey, file);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', Phoenix.uploadUrl, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // TODO: Display success/error message
            }
        };
        xhr.send(formData);
    });
})(jQuery, window.Phoenix || {});
