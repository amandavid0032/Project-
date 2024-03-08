
$(document).ready(function() {
    $('#myForm').submit(function(event) {
        $('.error-message').remove();
        var fileInput = $('#fileToUpload');
        var allowedExtensions = ['jpg', 'jpeg', 'png'];
        var fileName = fileInput.val();
        var fileExtension = fileName.split('.').pop().toLowerCase();
        if (fileName === '') {
            fileInput.after('<div class="error-message">Please select an image file.</div>');
            event.preventDefault();
        } else if ($.inArray(fileExtension, allowedExtensions) === -1) {
            fileInput.after('<div class="error-message">Please select a valid image file (jpg, jpeg, or png).</div>');
            event.preventDefault();
        }

        var requiredFields = ['firstname', 'lastname','fathername','mothername', 'email', 'password', 'confirm_password', 'street', 'zip_code', 'place', 'country', 'code', 'phone_number'];
        $.each(requiredFields, function(index, fieldName) {
            var inputField = $('#' + fieldName);
            if (inputField.val().trim() === '') {
                inputField.after('<div class="error-message">Please enter ' + fieldName.replace('_', ' ') + '.</div>');
                event.preventDefault(); 
            }
        });

        var emailInput = $('input[name="email"]');
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.val().trim())) {
            emailInput.after('<div class="error-message">Please enter a valid email address.</div>');
            event.preventDefault();
        }

        var passwordInput = $('input[name="password"]');
        var confirmPasswordInput = $('input[name="confirm_password"]');
        if (passwordInput.val() !== confirmPasswordInput.val()) {
            confirmPasswordInput.after('<div class="error-message">Passwords do not match.</div>');
            event.preventDefault();
        }

        var phoneInput = $('input[name="phone_number"]');
        var phoneRegex = /^\d{10}$/;
        if (!phoneRegex.test(phoneInput.val())) {
            phoneInput.after('<div class="error-message">Please enter a valid phone number.</div>');
            event.preventDefault();
        }

        var genderInputs = $('input[name="gender"]:checked');
        if (genderInputs.length === 0) {
            $('input[name="gender"]').last().after('<div class="error-message">Please select a gender.</div>');
            event.preventDefault(); 
        }
    });
});