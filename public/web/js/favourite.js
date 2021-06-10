$(document).on('click', '#favoriteBtn', function () {
var url = $(this).attr('href');
var icon = $(this).find('i');
      $.get(url, null, function (data) {
          console.log(data);
            if (data['status']) {
                  $(this).addClass('btn-active');
                  icon.removeClass('far').addClass('fas');
            } else {
                  $(this).removeClass('btn-active');
                  icon.removeClass('fas').addClass('far');
            }
      });

return false;
});