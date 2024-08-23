jQuery('#login_form').validate({
    rules:{
        uname:{
            required:true,
            minlength:4,
            
        },
        upass:{
            required:true,
            minlength:4,
        },

    }, messages:{
        uname:{
            required:"** Please enter your username**",
            minlength:"** Username cann't be less than 4 characters**"
        },
        upass:{
            required:"** Please enter your password **",
            minlength:"** Password must be at least 4 characters **"

        },

    }
})

jQuery('#edit_user').validate({
    
    rules:{
        u_name:{
            required:true,
            minlength:4,
        },
        u_mobile:{
            required:true,
            number:true,
            minlength:10,
            maxlength:10,
        },
        
       u_address:{
           required:true,
           minlength:6,

       },
        u_email:{
            required:true,
            email:true,

        },
        u_rating:{
            required:true,
            number:true,
        }

    },messages:{
        u_name:{
            required:"** Please enter your user name **",
            minlength:"** Name must be at least 4 charecters **"
        },
        u_mobile:{
            required:"** Please enter your mobile number**",
            minlength:"** contact number must be 10 digits**",
            maxlength: "** contact number cann't be more than 10 digits **"
        },
        
        u_email:{
            required: "** Please enter user email address **",
            
        },
        u_rating:{
            required:"** Please enter rating of the user here **",

        }

    }
})


jQuery('#edit_book').validate({

    rules:{
        book_name:{
            required:true,
            minlength:4,
        },
        book_genre:{
            required:true,
            minlength:5,

        },
        
       book_author:{
           required:true,
           minlength:5,

       },
        book_edition:{
            required:true,
            number:true,

        },
        book_description:{
            required:true,
            
        },
        book_rating:{
            required:true,
            number:true
        }

    },messages:{
        book_name:{
            required:"** Please enter your book name **",
            minlength:"** Name must be at least 4 charecters **"
        },
        book_genre:{
            required:"** Please enter your genre type of book**",
            minlength:"** genre must be 5 digits**",
            
        },
        
        book_author:{
            required: "** Please enter author name **",
            minlength:"** author name must of 5 characters **",
            
        },
        book_edition:{
            required:"** Please enter edtion of the book **",
            number :"** only a valid number **"
            

        },
        book_description:{
            required:"** Description is mandatory **"
        },

        book_rating:{
            required:"** Rating must be given for this book **",
            number:"** Must be a number or float value **"
        }

    }
});

// $.validator.addMethod(
//     "filesize",(value, element, param) =>{
//         const limit = parseInt(param) * 1024 * 1024;
//         const size = element.files[0].size;
//         if (size > limit){
//             return false;
//         } 
//         return true;
//     },
//     "File size should be less than {0}mb"

//     );
jQuery('#setting').validate({

    rules:{
        site_title:{
            required:true,
            minlength:5
        },
        mail_from:{
            required:true,
            email:true,
        },
        welcome_text:{
            required:true,
            minlength:5
        },
        logo:{

            extension:"png|jpg|jpeg"
            
        },
        submitHandler: function(form) {  
            if ($(form).valid()) 
                form.submit(); 
            return false; 
        }
     
     

    },
    messages:{
        site_title:{
            required:"** Please enter the website name **",
            minlength:"** site title should be at least 5 charecters**"
        },

        mail_from:{
            required:"** please enter mail address**"
        },
        welcome_text:{
            required:"** please enter welcome message **"
        },
        logo:{
            extension:"** Only Photos are allowed **"


        }
}
})