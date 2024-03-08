$(document).ready(function () {
    // user-view load code 
    function loadTable(page) {
        $.ajax({
            url: "admin-view-list.php",
            type: "POST",
            data: {
                page_no: page
            },
            success: function (data) {
                $("#table-data").html(data);
            }
        });
    }
    loadTable();

    // pagination for user-view
    $(document).on("click", ".page-item a", function (e) {
        e.preventDefault();
        var page_id = $(this).attr("id");
        loadTable(page_id);
    });

    // Search code
    function loadsearch(page) {
        var search_term = $("#searchInput").val();
        $.ajax({
            url: "admin-view-list.php",
            type: "POST",
            data: {
                search: search_term,
                page_no: page
            },
            success: function (data) {
                $("#table-data").html(data);
         
            }
        });
    }
    // Trigger search on keyup
    $(document).on("keyup", "#searchInput", function () {
        loadsearch();
    });
    
    // code for pagination search
    $(document).on("click", ".Search-item a", function (e) {
        e.preventDefault();
        var page_id = $(this).attr("id");
        loadsearch(page_id);
    });

    // Rest Code 
    $("#resetButton").click(function () {
        $("#searchInput").val('');
        loadTable();
    });

    //Delete Code
    $(document).on("click", ".btn.btn-danger", function () {
        var confirmDelete = confirm("Do You Really want to Delete this record ?");
        var userId = $(this).data("id");
        var element = this;
        if (confirmDelete) {
            $.ajax({
                url: "admin-view.php",
                type: "POST",
                data: {
                    id: userId
                },
                success: function (data) {
                    console.log("UserID:", userId);
                    if (data == 1) {
                        $(element).closest("tr").fadeOut();
                    } else {
                        $("#error-message").html("can't Delete Record.").slideDown();
                        $("#success-message").slideUp();
                    }
                },
                complete: function () {
                    loadTable();
                }
            });
        }
    });
});

// View  Code
$(document).on("click", ".btn.btn-info", function () {
    var confirmEdit = confirm("Do You Really want to See this record ?")
    var updateId = $(this).data("eid");
    if (confirmEdit) {
        $.ajax({
            url: "view-update-user.php",
            type: "POST",
            data: {
                id: updateId
            },
            success: function (data) {
                console.log(data)
                $("#modal .modal-content").html(data);
                $("#modal").modal("show");
            }
        });
    }
});


