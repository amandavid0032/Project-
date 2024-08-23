function bookdetails(str) {
    $('#bookfeedback-div').show(1000);

    $.ajax({
        url: 'action.php?type=bookfeedback',
        type: 'post',
        dataType: 'json',
        data: { 'book_id': str },
        success: function (res) {
            $(document).find('#bookfeedback-div').html(res.html1);
            $(document).find('#allfeedback-div').html(res.html2);
            $('#feedback-form').submit(function (e) {
                e.preventDefault();
                var feedback = $('#feedback').val();
                $.ajax({
                    url: 'action.php?type=insertfeedback',
                    type: 'post',
                    dataType: 'json',
                    data: { 'feedback': feedback, 'bookid': str },
                    success: function (res) {
                        $(document).find('#allfeedback-div').html(res.feedbackhtml);
                        $(document).find('#feedbackmsg').html(res.feedbackmsg);
                    }
                })
            });
        }
    });
    
}
$(document).ready(function () {
    $('#book-search').keyup(function () { 
        var bookdata = $(this).val();
       
        if(bookdata != '') 
        {
            $("#search-data-div").css("display","flex");
            $('#search-data-div').show();
            $(document).find('.allbook-div').hide();
            $(document).find('.pagi-div').hide(); 
              
            $.ajax({
                url:'action.php?type=search',
                type:'post',
                data:{ 'bookdata': bookdata },
                success:function(data)
                { 
                    $('#search-data-div').html(data);
                    
                }
            });
        } else {
            $("#search-data-div").css("display","none");
            $('#search-data-div').hide();
            $('.allbook-div').show();
            $('.pagi-div').show();
               
        }
        
    });
});