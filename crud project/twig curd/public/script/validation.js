$(document).ready(function () {
    $('#myForm').submit(function (event) {
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        var isValid = true;
        if (!validateOtherFields()) {
            isValid = false;
        }
        if (!validateImage()) {
            isValid = false;
        }
        if (!isValid) {
            event.preventDefault();
        }
    });
});

function validateImage() {
    var fileInput = $('input[name="image[]"]');
    var fileName = fileInput.val();
    var allowedExtensions = ['jpg', 'jpeg', 'png'];
    var fileExtension = fileName.split('.').pop().toLowerCase();
    var existingImage = $('input[name="existing_image"]').val();
    // Check if the file already exists
    if (existingImage && existingImage.includes(fileName)) {
        return true; // Image already exists, no need for validation
    }
    if (fileName === '' || $.inArray(fileExtension, allowedExtensions) === -1) {
        fileInput.addClass('is-invalid');
        fileInput.after('<div class="error-message">Please select a valid image file (jpg, jpeg, or png).</div>');
        return false;
    }
    return true;
}
function validateOtherFields() {
    var isValid = true;
    var requiredFields = ['firstname', 'lastname', 'fathername', 'mothername', 'email', 'street', 'zip_code', 'place', 'country', 'code', 'phone_number', 'password', 'confirm_password'];
    $.each(requiredFields, function (index, fieldName) {
        var inputField = $('#' + fieldName);
        if (inputField.val().trim() === '') {
            inputField.addClass('is-invalid');
            inputField.after('<div class="error-message">Please enter ' + fieldName.replace('_', ' ') + '.</div>');
            isValid = false;
        }
    });

    // Check email format
    var emailInput = $('input[name="email"]');
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(emailInput.val().trim())) {
        emailInput.addClass('is-invalid');
        emailInput.after('<div class="error-message">Please enter a valid email address.</div>');
        isValid = false;
    }

    // Check password match
    var passwordInput = $('input[name="password"]');
    var confirmPasswordInput = $('input[name="confirm_password"]');
    if (passwordInput.val() !== confirmPasswordInput.val()) {
        passwordInput.addClass('is-invalid');
        confirmPasswordInput.addClass('is-invalid');
        confirmPasswordInput.after('<div class="error-message">Passwords do not match.</div>');
        isValid = false;
    }

    // Check phone number format
    var phoneInput = $('input[name="phone_number"]');
    var phoneRegex = /^\d{10}$/;
    if (!phoneRegex.test(phoneInput.val())) {
        phoneInput.addClass('is-invalid');
        phoneInput.after('<div class="error-message">Please enter a valid phone number.</div>');
        isValid = false;
    }

    // Check gender selection
    var genderInputs = $('input[name="gender"]:checked');
    if (genderInputs.length === 0) {
        $('input[name="gender"]').last().addClass('is-invalid');
        $('input[name="gender"]').last().after('<div class="error-message">Please select a gender.</div>');
        isValid = false;
    }

    return isValid;
}