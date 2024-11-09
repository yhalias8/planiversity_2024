$(document).ready(function() {
   $("#footer").css("top",$(document).height()-62);
   $("#doc_height").val($(document).height());
   $("#map").css("height",$(document).height()-62);
   check_height();
});   
function check_height(){ 
var doc_height = $("#doc_height").val();
var win_height = parseInt($(window).height());
    if(doc_height>win_height){ 
        $("#footer").animate({top: win_height-62});
        $("#map").css("height",$(document).height()-62);
    }
		setTimeout(function(){
           check_height();
          }, 800);
} 