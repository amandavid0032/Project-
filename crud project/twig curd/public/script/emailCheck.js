$(document).ready(function () {
    $('#email').on('input', function () {
        var email = $(this).val();
        console.log(email);
        $.ajax({
            url: 'emailCheck.php',
            type: 'post',
            data: { email: email },
            success: function (response) {
                console.log(response);
                if (response== 1) {
                    $('#email-error').text('Email already exists');
                } else {
                    $('#email-error').text('');
                }
            },
        });
    });
});
