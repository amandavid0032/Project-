jQuery(document).ready(function ($) {
    $('.fadeOut').owlCarousel({
      items: 1,
      animateOut: 'fadeOut',
      loop: true,
      autoplayTimeout: 3000,
      dots: false,
      nav: true,
      autoplay: true,
      autoplayHoverPause: true,
      margin: 10,
      navText: ['<span class="slide-prev-nav"><i class="fa fa-angle-left"></i></span>', '<span class="slide-next-nav"><i class="fa fa-angle-right"></i></span>'],
    });


    $('.All-category').slice(0, 3).show();
    $('#loadMore').click(function () {
      $('.All-category:hidden').slice(0, 3).fadeIn(300);
      if ($('.All-category:hidden').length == 0) {
        $(this).fadeOut(300);
      }
    });
    $('.recent-one').slice(0, 10).show();
    $('.most-one').slice(0, 10).show();
    // for search
    $('.search-one').slice(0, 8).show();
    $('#loadMoreSearch').click(function () {
      $('.search-one:hidden').slice(0, 8).fadeIn(300);
      if ($('.search-one:hidden').length == 0) {
        $(this).fadeOut(300);
      }
    });

    $('.genreGroup').owlCarousel({
      loop: true,
      margin: 10,
      dots: false, 
      responsiveClass: true,
      responsive: {
        0: {
          items: 1,
          nav: true
        },
        600: {
          items: 3,
          nav: false
        },
        1000: {
          items: 4,
          nav: true,
          loop: false,
          margin: 20
        }
      },
      navText:['<span class="slide-prev-nav"><i class="fa fa-angle-left"></i></span>','<span class="slide-next-nav"><i class="fa fa-angle-right"></i></span>'],
    })


  });
