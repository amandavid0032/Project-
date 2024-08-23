
function blockBook(id)
{
    var conf = confirm("Are you sure want to block this book");
    var action = "block_book";
    if (conf == true){
        $.ajax({
            // url : "bookEditHandler.php",
            url : "actions.php",
            // type : "POST",
            type : "GET",
            dataType: 'json',
            data : { id : id, action : action },
            success : function(data){
                
                console.log(data.html);
                $(document).find('#bookListDiv').html(data.html);
            }
        })
    }
}

function unBlockBook(id)
{
    var conf = confirm("Are you sure want to Unblock this book");
    var action = "unblockBook";
    if (conf == true){
        $.ajax({
            // url : "bookEditHandler.php",
            url : "actions.php",
            // type : "POST",
            type : "GET",
            data : { id : id, action : action },
            success : function(data){
                // var row = document.getElementById("tr"+Num);
                // row.parentNode.removeChild(row);
                var parsed_data = jQuery.parseJSON( data );
                console.log(parsed_data);
                $(document).find('#bookListDiv').html(parsed_data.html);
            }
        })
    }
}



function deleteBook(id)
{
    // console.log("i am here");
    var conf = confirm("Are you sure want to delete this book");
    var action = "delete_book";
    if (conf == true){
        $.ajax({
            url : "actions.php",
            type : "GET",
            dataType: 'json',
            data : { id : id, action : action },
            success : function(data) {
                // console.log("data is "+data.html);
                // var parsed_data = jQuery.parseJSON(data);
                // console.log(data);
                $(document).find('#bookListDiv').html(data.html);
                // $("#bookListDiv").empty();
                // $("#bookListDiv").html(data.html);
            }
        });
    }
}


// function getBookDetails(id)
// {
//     $('#hiddenBookId').val(id);

//     $.post('bookEditHandler.php', { bookID : id }, function(data, status){
//             var book = JSON.parse(data);
//             $('#b_name').val(book.book_name);
//             $('#b_genre').val(book.genre);
//             $('#b_author').val(book.author);
//             $('#b_edition').val(book.edition);
//             $('#b_description').val(book.description);
//             $('#b_rating').val(book.rating);

//     })
//     $('#editBookModal').modal('show');
// }

function getBookDetails(id)
{
    $.ajax({
        url : "bookEditHandler.php",
        type : "POST",
        data : { bookID : id },
        success : function(data) {
            var parsed_data = jQuery.parseJSON( data );
            // console.log(parsed_data);
            $(document).find('#bookListDiv').html(parsed_data.html);
        }
    })

}

function updateBookDetails()
{
    var isValid = $('form').valid();
    if(!isValid) {
      e.preventDefault();
    }

    var bookId = $('#hiddenBookId').val();
    var book_name = $('#b_name').val();
    var book_genre = $('#b_genre').val();
    var book_author = $('#b_author').val();
    var book_edition = $('#b_edition').val();
    var book_description = $('#b_description').val();
    // var book_rating = $('#b_rating').val();
    $.post('bookEditHandler.php', { BookId : bookId, bookName:book_name, bookGenre:book_genre, bookAuthor:book_author, bookEdition:book_edition, bookDescription:book_description}, function(data, status){
        var parsed_data = jQuery.parseJSON( data );
        console.log(parsed_data);
        $(document).find('#bookListDiv').html(parsed_data.html);
        
    });
    
}



