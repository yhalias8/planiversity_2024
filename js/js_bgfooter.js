/*$(document).ready(function() {
   $("#footer").css("top",$(document).height()-62);
   $(".bg").css("height",$(document).height()-62);
   $("#doc_height").val($(document).height());
   check_height();
});   
function check_height(){ 
var doc_height = $("#doc_height").val();
var win_height = parseInt($(window).height());
    //if(doc_height>win_height){ 
        $("#footer").animate({top: $(document).height()-62});
        $(".bg").animate({height: document.body.scrollHeight});
   // }
		setTimeout(function(){
           check_height();
          }, 800);
} */
$(document).ready(function() {
   $("#footer").css("top",$(document).height()-62);
   $(".bg").css("height",$(document).height()-62);
   $("#doc_height").val($(document).height());
   check_height();
});   
function check_height(){ 
var doc_height = $("#doc_height").val();
var win_height = parseInt($(window).height());
    if(doc_height>win_height){ 
        $("#footer").animate({top: doc_height-62});
        $(".bg").animate({height: doc_height});
    }
		setTimeout(function(){
           check_height();
          }, 800);
} 

