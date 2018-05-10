$(function() {
  $('.button-like').click(function() {
    var button = $(this);
    $.post(
      '/post/default/like',
      {
        id: $(this).data('id')
      },
      function(data) {
        if (data.success) {
          button.addClass('hidden');
          button.siblings('.button-unlike').removeClass('hidden');
          button.siblings('.likes-count').text(data.likesCount);
        }
      }
    );
    return false;
  });

  $('.button-unlike').click(function() {
    var button = $(this);
    $.post(
      '/post/default/unlike',
      {
        id: $(this).data('id')
      },
      function(data) {
        if (data.success) {
          button.addClass('hidden');
          button.siblings('.button-like').removeClass('hidden');
          button.siblings('.likes-count').text(data.likesCount);
        }
      }
    );
    return false;
  });
});
