$(document).ready(function(){

//$('#demo').hover(
//  function () {
//    $(this).toggle();
//
//});

  $('.image_container').click(function(){
    var user_input;

    location.reload();
    return user_input = confirm("Are you sure you want to delete this file");
  });


});