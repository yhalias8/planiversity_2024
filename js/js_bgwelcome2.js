/*$(document).ready(function() {
	$("#doc_height").val($.getDocHeight());	
	$.getDocHeight = function(){
		 var D = document;
		 return Math.max(Math.max(D.body.scrollHeight,    D.documentElement.scrollHeight), Math.max(D.body.offsetHeight, D.documentElement.offsetHeight), Math.max(D.body.clientHeight, D.documentElement.clientHeight));
	};
   $("#footer").css("top",$.getDocHeight()-150);
   $(".bg").css("height",$.getDocHeight()-150);
   //$("#doc_height").val($.getDocHeight());
   check_height();
});   

function check_height(){ 
var doc_height    = $("#doc_height").val();
var actual_height = $.getDocHeight();

      if(doc_height<(actual_height-1)){ 
	    //$("#doc_height").val(actual_height);
        $("#footer").css("top",doc_height-150);
        $(".bg").css("height",doc_height-150);
      }else{
		$("#footer").css("top",actual_height-150);
        $(".bg").css("height",actual_height-150);
		}
		setTimeout(function(){
           check_height();
          }, 500);
} */


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

function getDocHeight() {
    var D = document;
    return Math.max(
        D.body.scrollHeight, D.documentElement.scrollHeight,
        D.body.offsetHeight, D.documentElement.offsetHeight,
        D.body.clientHeight, D.documentElement.clientHeight
    );
}