$(document).ready(function () {
    $('#login').click(function(){
        $('#login-div').show();
        $('#register-div').hide();
        $('#forget-div').hide();
    })
    $('#register').click(function(){
        $('#login-div').hide();
        $('#register-div').show();
        $('#forget-div').hide();
    })
});