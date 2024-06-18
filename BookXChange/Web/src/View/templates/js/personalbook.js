
$(document).ready(function () {

    jQuery.validator.addMethod("mysize", function (value, element) {
        var img = $('#book_image').val();
        if (img != '') {
            var numb = $("input[name='book_image']")[0].files[0].size;
            if (numb > 1048576) {
                return false;
            } else {
                return true;
            }

        } else {
            return true
        }
    }, "too large, Image must be 2mb");

    jQuery.validator.addMethod("mytype", function (value, element) {
        var img = $('#book_image').val();
        if (img != '') {
            var file_data = $("input[name='book_image']")[0].files;
            var myfile = file_data[0].name;
            var mytype = myfile.substring(myfile.lastIndexOf(".") + 1);
            console.log(mytype);
            if (mytype == 'jpeg' || mytype == 'jpg' || mytype == 'png' || mytype == 'svg') {
                return true;
            } else {
                return false;
            };
        }
        else {
            return true
        }
    }, "Img type must be jpeg, jpg, svg, png");
    $("#editbook-form").validate({
        rules: {
            book_image: {
                mysize: true,
                mytype: true,
            },
            book_name: {
                required: true,
            },
            book_genre: {
                required: true,
            },
            book_author: {
                required: true,
            },
            book_publisher: {
                required: true,
            },
            book_edition: {
                required: true,
            },
            book_isbn: {
                required: true,
            },
            book_langauge: {
                required: true,
            },
            bookcondition: {
                required: true,
            },
            book_rating: {
                required: true,
                number: true,
            },
            book_des: {
                required: true,
            }
        }, messages: {

            book_name: {
                required: "Please Enter Book Name!",
            },
            book_genre: {
                required: "Please Enter Book Genre!",
            },
            book_author: {
                required: "Please Enter Book Author!",
            },
            book_publisher: {
                required: "Please Enter Book Publisher!",
            },
            book_edition: {
                required: "Please Enter Book Edition!",
            },
            book_isbn: {
                required: "Please Enter Book ISBN!",
            },
            book_langauge: {
                required: "Please Enter Book langauge!",
            },
            bookcondition: {
                required: "Please Select book condition!",
            },
            book_des: {
                required: "Please Enter Book Description!",
            },
            book_rating: {
                required: "Please Enter Book Rating!",
                number: "Please Enter Number Format!",
            }

        }
    })
});
function starmark(item)
{
   var count  = item.value;
   $('#rating').val(count);
}
$(document).ready(function () {
rating = $('#rating').val();
 {
    for(i=0;i<=rating*2;i++) {
        $('#rating'+(i)).attr("checked","checked");
    }
 }
});

