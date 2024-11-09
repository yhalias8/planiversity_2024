$(document).ready(function() {
   $("#map").css("height",scrollHeight()-50);
   check_height();
});   
function check_height(){ 
        $("#map").css("height",scrollHeight()-50);
		setTimeout(function(){
           check_height();
          }, 200);
} 
function scrollHeight(){ scroll_Height = Math.max(
	  document.body.scrollHeight, document.documentElement.scrollHeight,
	  document.body.offsetHeight, document.documentElement.offsetHeight,
	  document.body.clientHeight, document.documentElement.clientHeight
	 );
  return scroll_Height;
}

/*$(document).ready(function() {
$.getDocHeight = function(){
     var D = document;
     return Math.max(Math.max(D.body.scrollHeight,    D.documentElement.scrollHeight), Math.max(D.body.offsetHeight, D.documentElement.offsetHeight), Math.max(D.body.clientHeight, D.documentElement.clientHeight));
};
//alert( $.getDocHeight() );
//alert( getDocHeight() )

   $("#footer").css("top",$.getDocHeight()-50);
   $("#map").css("height",$.getDocHeight()-0);
   //$("#doc_height").val($.getDocHeight());
   check_height();
});   

function check_height(){ 
var doc_height = $("#doc_height").val();
var win_height = parseInt($(window).height());
    //if(doc_height>win_height){ 
   $("#footer").css("top",$.getDocHeight()-50);
   $("#map").css("height",$.getDocHeight()-0);
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
}*/

/*$(document).ready(function() {
 jQuery.fn.centerElement = function () {
   this.css ("position", "relative");
   this.css ("margin-left", ($ (window). width () - this.width ()) / 2 + $ (window). scrollLeft () + "px")
   return this;
   }  
center_cont();
});

function center_cont(){ 
    $('.cont_blue').centerElement();
    setTimeout(function(){
           center_cont();
          }, 200);
}*/