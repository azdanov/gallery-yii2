jQuery(document).ready(function($) {
  // Scroll to top
  $('.back-to-top-page').each(function() {
    $('.back-to-top').on('click', function(event) {
      event.preventDefault();
      $('html, body').animate({ scrollTop: 0 }, 200);
      return false;
    });
  });
});
