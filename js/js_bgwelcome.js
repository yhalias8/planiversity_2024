
$(document).ready(function() {
	
$.getDocHeight = function(){
     var D = document;
     return Math.max(Math.max(D.body.scrollHeight,    D.documentElement.scrollHeight), Math.max(D.body.offsetHeight, D.documentElement.offsetHeight), Math.max(D.body.clientHeight, D.documentElement.clientHeight));
};
   $("#footer").css("top",$.getDocHeight()-160);
   $(".bg").css("height",$.getDocHeight()-160);
   //$("#doc_height").val($.getDocHeight());
   check_height();
});   

function check_height(){ 
var doc_height = $("#doc_height").val();
var win_height = parseInt($(window).height());
    //if(doc_height>win_height){ 
      $("#footer").css("top",$.getDocHeight()-160);
      $(".bg").css("height",$.getDocHeight()-160);
   // }
		setTimeout(function(){
           check_height();
          }, 500);
} 

