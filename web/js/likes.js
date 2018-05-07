$(function() {
  $('.btn-like').click(function() {
    var button = $(this);
    $.post(
      '/post/default/like',
      {
        id: $(this).data('id')
      },
      function(data) {
        if (data.success) {
          button.addClass('hidden');
          button.siblings('.btn-unlike').removeClass('hidden');
          button
            .siblings('span')
            .children('.likes-count')
            .text(data.likesCount);
        }
      }
    );
  });

  $('.btn-unlike').click(function() {
    var button = $(this);
    $.post(
      '/post/default/unlike',
      {
        id: $(this).data('id')
      },
      function(data) {
        if (data.success) {
          button.addClass('hidden');
          button.siblings('.btn-like').removeClass('hidden');
          button
            .siblings('span')
            .children('.likes-count')
            .text(data.likesCount);
        }
      }
    );
  });
});
