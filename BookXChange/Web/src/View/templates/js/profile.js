$(document).ready(function(){
    jQuery.validator.addMethod("my_size", function (value, element) {
        var img = $('#new_user_image').val();
        if (img != '') {
            var numb = $("input[name='newimage']")[0].files[0].size;
            if (numb > 1048576) {
                return false;
            } else {
                return true;
            }
        } else {
            return true
        }
    }, "too large, Image must be 2mb");

    jQuery.validator.addMethod("my_type", function (value, element) {
        var img = $('#new_user_image').val();
        if (img != '') {
            var file_data = $("input[name='newimage']")[0].files;
            var myfile = file_data[0].name;
            var mytype = myfile.substring(myfile.lastIndexOf(".") + 1);
            console.log(mytype);
            if (mytype == 'jpeg' || mytype == 'jpg' || mytype == 'png' || mytype == 'svg') {
                return true;
            } else {
                return false;
            }
        } else {
            return true
        }

    }, "Img type must be jpeg, jpg, svg, png");  
$('#profile-form').validate({
    rules:{
        newimage:{
            my_type:true,
            my_size:true,
        },
        firstname:{
            required:true,
            minlength:5,
        },
        user_phone:{
            required:true,
            minlength:10,
            maxlength:10,
            number:true,

        },
        user_address:{
            required:true,
            minlength:5,
        },
        user_email:{
            required:true,
            email:true,
        }

    }, messages:{

        firstname:{
            required:"Please Enter First Name !",
            minlength:"Please Enter at least 5 character !",
        },
        user_phone:{
            required:"Please Enter Your Phone !",
            number:"Please Enter Number Format !",
            minlength:"Please Enter 10 digit Phone Number !",
            maxlength:"Please Enter 10 digit Phone Number !",

        },
        user_address:{
            required:"Please Enter Your Address !",
            minlength:"Please Enter at least 10 character !",
        },
        user_email:{
            required:"Please Enter Your Email !",
            email:"Please Enter Valid Email !",
        }
    }
})
$('#reset-password-form').validate({
    rules:{
        old_user_password:{
            required:true,
            minlength:5,
        },
        new_user_password:{
            required:true,
            minlength:5,
        },
        confirmPassword:{
            required:true,
            equalTo:'#new_user_password',
        }
    },messages:{
        old_user_password:{
            required:"Please Enter Your Old Password !",
            minlength:"Please Enter at least 5 character !",
        },
        new_user_password:{
            required:"Please Enter New Passowrd !",
            minlength:"At least 5 character !",
        },
        confirmPassword:{
            required:"Please Enter Confirm Password!",
            equalTo:"Password Not match!",
        }
    }
})
});

$(document).ready(function(){
    $('#reset-pass-btn').click(function () {

        $('#reset-password-form').show();
        $('#profile-form').hide();
        $('#accountTitle').hide();
        $('.subTitle').html('Change Password');
        $('.reset-click').hide();
        $('.editTitle').show();
    })
    $('.editTitle').hide();
    $('.upload-pic').hide();
    $('.submit-profile').hide();
    $('.edit-btn').click(function (e) {
        $('.editTitle').show();
        $('.edit-btn-div').hide();
        $('.submit-profile').show();
        $('#firstname').attr("readonly", false);
        $('#lastname').attr('readonly', false);
        $('#address').attr('readonly', false);
        $('.upload-pic').show();

    })
})