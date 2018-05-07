$(function() {
  var $buttonLike = $('#buttonLike');
  var $buttonUnlike = $('#buttonUnlike');
  var $likesCount = $('#likesCount');

  $buttonLike.click(function() {
    $.post(
      '/post/default/like',
      {
        id: $(this).data('id')
      },
      function(data) {
        if (data.success) {
          $buttonLike.addClass('hidden');
          $buttonUnlike.removeClass('hidden');
          $likesCount.text(data.likesCount);
        }
      }
    );
  });

  $buttonUnlike.click(function() {
    $.post(
      '/post/default/unlike',
      {
        id: $(this).data('id')
      },
      function(data) {
        if (data.success) {
          $buttonUnlike.addClass('hidden');
          $buttonLike.removeClass('hidden');
          $likesCount.text(data.likesCount);
        }
      }
    );
  });
});
