$(document).ready(function () {
    jQuery.validator.addMethod("my_size", function (value, element) {
        var numb = $("input[name='book_image']")[0].files[0].size;
        if (numb > 1048576) {
            return false;
        } else {
            return true;
        }
    }, "too large, Image must be 2mb");

    jQuery.validator.addMethod("my_type", function (value, element) {
        var file_data = $("input[name='book_image']")[0].files;
        var myfile = file_data[0].name;
        var mytype = myfile.substring(myfile.lastIndexOf(".") + 1);
        console.log(mytype);
        if (mytype == 'jpeg' || mytype == 'jpg' || mytype == 'png' || mytype == 'svg') {
            return true;
        } else {
            return false;
        }

    }, "Img type must be jpeg, jpg, svg, png");
    $("#addbook-form").validate({
        rules: {
          
            book_name: {
                required: true,
            },
            book_genre: {
                required: true,
            },
            book_author: {
                required: true,
            },
            book_edition: {
                required: true,
            },
            book_publisher:{
                required:true,
            },
            book_isbn:{
                required:true,
                number:true,
                minlength:13,
                maxlength:13,
                
            },
            book_des: {
                required: true,
            },
            book_language:{
                required:true,
            },
            bookcondition:{
                required:true,
            },
            book_image: {
                required: true,
                my_size: true,
                my_type: true,
            },
        }, messages: {
           
            book_name: {
                required: "Please enter Book Name!",
            },
            book_genre: {
                required: "Please choose Book Genre!",
            },
            book_author: {
                required: "Please enter Book Author!",
            },
            book_edition: {
                required: "Please enter Book Edition!",
            },
            book_publisher:{
                required:"Please enter Publisher!",
            },
            book_isbn:{
                required:"Please enter ISBN !",
                minlength:"Please enter 13 digit number code!",
                maxlength:"Please enter 13 digit number code!",
                number:"Please enter only number format!"
            },
            book_des: {
                required: "Please enter book Description!",
            },
            book_language:{
                required:"Please choose Language!",
            },
            bookcondition:{
                required:"Please choose Book Condtion!",
            },
            book_image:{
                required:"Please choose Book Image!"
            }
        }
    })
});
function starmark(item)
 {
	var count  = item.value;
	$('#rating').val(count);
 }