function reject() {
    $(document).find('#request-reject-div').show();
}
function grant() {
    var a = confirm('Do you really want to confirm return request?');
    if (a == true) {
        $(document).find('#return-grand-div').show();
    } else {
        e.preventDefault();
    }
}
$(document).ready(function(){
     
    $('#request-closer').click(function () {
        $(document).find('#request-reject-div').hide()
        $(document).find('#reject-form')[0].reset();

    })
    
    $('#grand-closer').click(function () {
        $(document).find('#return-grand-div').hide()
        $(document).find('#returngrand-form')[0].reset();

    })
    $('#returngrand-form').validate({
        rules: {
            requester_rating: {
                required: true,
                number: true,
            }
        }, messages: {
            requester_rating: {
                required: "Enter Rating",
                number: "must be in Number Format",
            }
        }
    })
    $('#reject-form').validate({
        rules: {
            reason: {
                required: true,
                minlength: 5,
            }
        }, messages: {
            reason: {
                required: "Enter Reason",
                minlength: "atleast 5 characters",
            }
        }
    })

})