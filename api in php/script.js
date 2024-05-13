$(document).ready(function () {
    function loadtable() {
        $.ajax({
            url: "http://localhost/New%20folder%20(2)/api%20in%20php/Api-feath.php",
            type: "GET",
            success: function (data) {
                if (data.success == false) {
                    $("#load-table").append("<tr><td colspan='7'><h2>" + data.message + "</h2></td></tr>");
                } else {
                    $.each(data, function (key, value) {
                        $("#load-table").append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + value.f_name + "</td>" +
                            "<td>" + value.l_name + "</td>" +
                            "<td>" + value.emailId + "</td>" +
                            "<td>" + value.phone + "</td>" +
                            "<td>" + value.gender + "</td>" +
                            "<td><button class='edit-btn' data-eid='" + value.id + "'>Edit</button> <button class='delete-btn' data-did='" + value.id + "'>Delete</button></td>" +
                            "</tr>");
                    });
                }
            }
        });
    }
    loadtable();
});


$(document).on("click", ".edit-btn", function () {
    $("#model").show();
    var studentid = $(this).data("eid");
    var obj = { sid: studentid };
    var myJSON = JSON.stringify(obj);
    $.ajax({
        url: "http://localhost/New%20folder%20(2)/api%20in%20php/api-single.php",
        type: "POST",
        data:myJSON,
        success:function(data){
            $("#first-name").val(data[0].f_name);
            $("#last-name").val(data[0].l_name);
            $("#Phone").val(data[0].phone);
            $("#email").val(data[0].emailId);
            $("#Gender").val(data[0].gender);
        }
    })
});

// Event listener for clicking on the close button
$(document).on("click", "#close-btn", function () {
    $("#model").hide(); // Hide the modal when close button is clicked
});