$(document).ready(function () {
    $('#register-email-form').validate({
        rules:{
            email:{
                required:true,
                email:true,   
            }
        },messages:{
            email:{
                required:"Please Enter Your Email!",
                email:"Please Enter Correct Email Format!"
            }
        }
    });
    $('#otp-form').validate({
        rules:{
            register_otp:{
                required:true,
                minlength:6,
                maxlength:6   
            }
        },messages:{
            register_otp:{
                required:"Please, Enter OTP!",
                minlength:"Please Enter 6 digit OTP!",
                maxlength:"Please Enter 6 digit OTP!"
            }
        }
    });
});