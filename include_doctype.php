<!doctype html>
<script type="text/javascript">
       var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };
	
   var newDoctype = document.implementation.createDocumentType(
 'html',
 '-//WAPFORUM//DTD XHTML Mobile 1.0//EN',
 'http://www.wapforum.org/DTD/xhtml-mobile10.dtd'
);

if( isMobile.any() ) document.doctype.parentNode.replaceChild(newDoctype,document.doctype);
</script>