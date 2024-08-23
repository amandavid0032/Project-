function bookrequest(bookid, ownerid) {
    $.ajax({
      url: 'action.php?type=bookrequest',
      type: 'post',
      dataType: 'json',
      data: { 'bookid': bookid, 'ownerid': ownerid },
      success: function (res) {
        console.log(res.request);
        if (res.request == true) {
          $(document).find('#request' + bookid).html('Requested');
          $(document).find('#request' + bookid).prop('disabled', true);
        }
      }
    })
  }

  function bookreturnrequest(bookid, ownerid) {
    $('#book-rating-div').show();
    $('#book-rating-form').submit(function (e) {
      e.preventDefault();
      var rating = $('#book_rating').val();
      var review = $('#review').val();
      console.log(rating);
      $.ajax({
        url: 'action.php?type=bookreturnrequest',
        type: 'post',
        dataType: 'json',
        data: { 'bookid': bookid, 'ownerid': ownerid, 'bookrating':rating,'review':review },
        success: function (res) {
          console.log(res.returnrequest);
          if (res.returnrequest == true) {
            $(document).find('#book-rating-div').hide();
            $(document).find('#book-rating-form')[0].reset();
            $(document).find('#returnrequest' + bookid).html('Return Requested');
            $(document).find('#returnrequest' + bookid).prop('disabled', true);
          }
        }
      })
    })
  }

  $(document).ready(function () {
    $('#book-rating-form').validate({
      rules: {
        book_rating: {
          required: true,
        }
      }, messages: {
        book_rating: {
          required: "Please Enter book rating",
        }
      }
    })
  });
  $(document).ready(function() {
    $('#book-rating-closer').click(function () {
      $(document).find('#book-rating-div').hide()
      $(document).find('#book-rating-form')[0].reset();
  })
  });