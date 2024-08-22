$(document).ready(function () {
    $(document).on("click", ".btn-info", function () {
        var userId = $(this).data("eid");
        $.ajax({
            url: "/user/" + userId,
            type: "GET",
            success: function (data) {
                $('.modal-content').html(data);
                $('#userData').modal('show');
            }
        });
    });
});
