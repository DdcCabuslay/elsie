function getContentHeight() {
  if ($(window).width() < 1024) {
    var content_height = $(window).height() - ($('.mdc-toolbar').height() + $('.bottom_bar_nav').height());
    if ($('#anime_list').height() > content_height) {
      $('#anime_list .mdc-list-group').css('padding-bottom', '0px');
    } else {
      $('#anime_list .mdc-list-group').css('padding-bottom', '60px');
    }
  } else {
    $('#anime_list .mdc-list-group').css('padding-bottom', '0px');
  }
}

$(window).ready(function() {
  $(':root').css('--mdc-theme-primary', '#388e3c');
  $('main').fadeIn(100);
  $('.mdc-linear-progress').delay(100).fadeOut(100);
  getContentHeight();
});

$(window).resize(function() {
  getContentHeight();
});

var lastScrollTop = 0;
$(window).scroll(function(event) {
  var position = $(this).scrollTop();
  if (position > lastScrollTop) {
    $('.bottom_bar_nav').addClass('bottom_bar_scroll');
    $('.mdc-toolbar').addClass('mdc-toolbar_hidden');
  } else {
    $('.bottom_bar_nav').removeClass('bottom_bar_scroll');
    $('.mdc-toolbar').removeClass('mdc-toolbar_hidden');
  }
  lastScrollTop = position;
});

$('#anime_list_nav a').click(function() {
  $('#anime_list section').fadeOut(100);
  var clicked_section = $(this).attr('id');
  if (clicked_section == 'watching_button') {
    $(':root').css('--mdc-theme-primary', '#388e3c');
    $('#watching_list').delay(100).fadeIn(100, function() {
      getContentHeight();
    });
  } else if (clicked_section == 'completed_button') {
    $(':root').css('--mdc-theme-primary', '#1976d2');
    $('#completed_list').delay(100).fadeIn(100, function() {
      getContentHeight();
    });
  } else if (clicked_section == 'on_hold_button') {
    $(':root').css('--mdc-theme-primary', '#f9a825');
    $('#on_hold_list').delay(100).fadeIn(100, function() {
      getContentHeight();
    });
  } else if (clicked_section == 'dropped_button') {
    $(':root').css('--mdc-theme-primary', '#d32f2f');
    $('#dropped_list').delay(100).fadeIn(100, function() {
      getContentHeight();
    });
  } else if (clicked_section == 'ptw_button') {
    $(':root').css('--mdc-theme-primary', '#616161');
    $('#ptw_list').delay(100).fadeIn(100, function() {
      getContentHeight();
      });
  }
  window.scrollTo(0, 0);
});

(function() {
  setTimeout(function() {
    window.navBar = new mdc.tabs.MDCTabBar(document.querySelector('.bottom_bar_nav'));
  }, 200)
})();

var about_dialog = new mdc.dialog.MDCDialog(document.querySelector('#about-dialog'));
document.querySelector('#about_option').addEventListener('click', function(evt) {
  about_dialog.show();
});