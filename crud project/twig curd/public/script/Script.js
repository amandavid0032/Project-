// code for admin view
function loadTable(page = 1, fn) {
    $.ajax({
        url: "UserViewList.php",
        type: "POST",
        data: {
            page_no: page,
        },
        success: function (data) {
            $("#table-data").html(data);
            if (fn) fn();
        }
    });
}


// Trigger search on keyup
$('#searchButton').click(function () {
    columnSorting();
});


var currentPageNumber = 1
function columnSorting(page_num, fn) {
    if (page_num !== undefined) {
        currentPageNumber = page_num;
    }
    var search_term = $("#searchInput").val();
    let coltype = '', colorder = '', classAdd = '', classRemove = '';
    $("th.sorting").each(function () {
        if ($(this).attr('colorder') != '') {
            coltype = $(this).attr('coltype');
            colorder = $(this).attr('colorder');
            console.log(colorder)

            if (colorder == 'asc') {
                classAdd = 'asc';
                classRemove = 'desc';
            } else {
                classAdd = 'desc';
                classRemove = 'asc';
            }
        }
    });

    $.ajax({
        type: 'POST',
        url: 'userViewList.php',
        data: { page: page_num, coltype: coltype, colorder: colorder, search: search_term },
        success: function (html) {
            $('#dataContainer').html(html);
            if (fn) fn();
            if (coltype != '' && colorder != '') {
                $("th.sorting").each(function () {
                    if ($(this).attr('coltype') == coltype) {
                        $(this).attr("colorder", colorder);
                        $(this).removeClass(classRemove);
                        $(this).addClass(classAdd);
                    }
                });
            }
        }
    });
}

$(function () {
    $(document).on("click", "th.sorting", function () {
        let current_colorder = $(this).attr('colorder');
        $('th.sorting').attr('colorder', '');
        $('th.sorting').removeClass('asc');
        $('th.sorting').removeClass('desc');
        if (current_colorder == 'asc') {
            $(this).attr("colorder", "desc");
            $(this).removeClass("asc");
            $(this).addClass("desc");
        } else {
            $(this).attr("colorder", "asc");
            $(this).removeClass("desc");
            $(this).addClass("asc");
        }
        columnSorting();
    });
});



// Reset button code
$("#resetButton").click(function () {
    $("#searchInput").val('');
    loadTable();
});



function showToast(message, type) {
    var toastElement = $(".toast");
    var toastBody = $(".toast-body");
    toastBody.text(message);
    toastElement.removeClass("bg-success bg-error");
    if (type.toLowerCase() === "success") {
        toastElement.addClass("bg-success");
    } else if (type === "error") {
        toastElement.addClass("bg-error");
    }
    toastElement.toast({ delay: 2000 }).toast("show");
    setTimeout(function () {
        toastElement.toast("hide");
    }, 5000);
}

// user view
$(document).ready(function ($) {
    $(document).on("click", ".btn.btn-success", function () {
        var updateId = $(this).data("eid");
        $.ajax({
            url: "userData.php",
            type: "POST",
            data: {
                id: updateId
            },
            success: function (data) {
                $('.modal-content').html(data);
                $('#userData').modal('show');
            }
        });
    });
});



// view code from update data of user
$(document).ready(function ($) {
    $(document).on("click", ".btn.btn-info", function () {
        var updateId = $(this).data("eid");
        $.ajax({
            url: "loginUserData.php",
            type: "POST",
            data: {
                id: updateId
            },
            success: function (data) {
                $('.modal-content').html(data);
                $('#userData').modal('show');
            }
        });
    });
});


// update user data 
$(document).on("submit", "#myForm", function (e) {
    e.preventDefault();
    var emptyFields = $(this).find('input[type="text"]').filter(function () {
        return $.trim($(this).val()) === '';
    });

    if (emptyFields.length > 0) {
        showToast("Please fill in all fields", "error");
        return;
    }

    var formData = new FormData(this);
    $.ajax({
        url: "userUpdate.php",
        type: "POST",
        contentType: false,
        processData: false,
        data: formData,
        success: function (data) {
            if (data == 1) {
                $('#userData').modal('hide');
                columnSorting(currentPageNumber, function () { showToast("Record updated successfully", "success") });
            } else {
                showToast("Failed to update record", "error");
            }
        },
        error: function () {
            showToast("Error in updating record", "error");
        }
    });
});



