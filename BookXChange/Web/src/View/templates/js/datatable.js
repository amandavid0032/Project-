$(document).ready(function () {
    $('#mybooktable').DataTable();
});
function bookdelete(id) {
    var conf = confirm('Are you sure want to delete this book!!');
    if (conf == false) {
        return false;
    } else {
        location.href = "action.php?deletebookid=" + id;
        return true;
    }
}