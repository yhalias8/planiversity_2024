
<script type="text/javascript">
function show_win(id)
   { //$('#link_'+id).toggle();
     $('#win_'+id).toggle('slow');     
   }
  $(document).ready(function() {
	  $('#show_loginform').click(function(){
		 $('.login_cont').show('fast');  
	  });
	   $('#login_cont_close').click(function(){
         $('.login_cont').hide('fast');  
      });
      $('#showhidepassword').click(function(){
          var x = document.getElementById("login_password");
            if (x.type === "password") {
                x.type = "text";
                document.getElementById("showhidepassword").src="<?php echo SITE; ?>images/if_misc-_eye_vision_1276868.png";
            } else {
                x.type = "password";
                document.getElementById("showhidepassword").src="<?php echo SITE; ?>images/if_misc-_eye_vision_1276868.png";
            }
		   
	  });
          //////// NEW CODE
	var $elems = $('.animateblock');
		  var winheight = $(window).height();
		  var fullheight = $(document).height();
		  
		  $(window).scroll(function(){
			animate_elems();
		  });
		  
		  function animate_elems() {
			wintop = $(window).scrollTop();
		 
			$elems.each(function(){
			  $elm = $(this);
			  
			  if($elm.hasClass('animated')) { return true; }
			  
			  topcoords = $elm.offset().top;
			  
			  if(wintop > (topcoords - (winheight*.75))) {			
				if ($elm.attr("title")) 
				   $elm.addClass('animated '+$elm.attr("title"));
				else if ($elm.attr("name")) 
				   $elm.addClass('animated '+$elm.attr("name"));
				else
				   $elm.addClass('animated');   
			  }		  
			});
		  }
		  
	var nav = $('.main_head');
	var slidernew = $('.cb-slideshow');		  	
			   
	$('a.more_det[href^="#"]').click(function(e) {
		var wintop = $(window).scrollTop();
	    var topcoords = slidernew.offset().top;
	    var winheight = $(window).height();
		var aux = topcoords - (winheight*.75);
		$('html,body').animate({ scrollTop: $(this.hash).offset().top}, aux);
		return false;
		e.preventDefault();
	});
        $(".h1click").click(function(){
            window.location.href = 'registration';
        });
	//////// END NEW CODE	
  });  
  
</script>

<header class="main_head">
     <div class="cont_in">
         <h1><a href="<?php echo SITE.$welcomelink; ?>"><img src="<?php echo SITE; ?>images/logo.png" class="logo" alt="" />Planiversity</a></h1>
         <nav class="nav-collapse">
              <ul class="main">
                <?php if ($auth->isLogged()) { ?>
                  <li><a href="<?php echo SITE; ?>welcome">Home</a></li>
                  <?php if ($userdata['account_type']=='Business' || $userdata['account_type']=='Admin') { ?>                  
                           <li><a href="<?php echo SITE; ?>calendar">Trip Calendar</a></li>
                <?php } ?>
                <?php } ?>
                <li><a href="<?php echo SITE; ?>about-us">About Us</a></li>
                <li><a href="<?php echo SITE; ?>faq">FAQ</a></li>
                <li><a href="<?php echo SITE; ?>select-your-payment">What it costs</a></li>
                <?php if ($auth->isLogged()) { ?>
                  <li><a href="<?php echo SITE; ?>logout">Log Out</a></li>
                <?php } else { ?>
                  <li><a id="show_loginform" class="login_nav" >Log in</a></li>
                <?php } ?>
              </ul>
         </nav>
        <script type="text/javascript">
          var navigation = responsiveNav(".nav-collapse");
        </script>
    </div>
</header>


<div class="login_cont">  
<div class="login_cont_in">     
     <a id="login_cont_close" class="close">X</a>
     <form name="login_form" method="POST">
        <h2>SIGN IN</h2>
        <div class="error_style"><?php echo $output; ?></div>            
        <input name="login_username" id="login_username" type="text" value="" maxlength="100" placeholder="Email or Customer ID">
        <input name="login_password" id="login_password" type="password" maxlength="50" value="" placeholder="Password">
        <div class="showhidepassword showhidepassword2"><img title="toggle password visibility" id="showhidepassword" src="<?php echo SITE; ?>images/if_misc-_eye_vision_1276868.png" alt="showorhidepassword" /></div>
        <input name="login_submit" id="login_submit" type="submit" value="Login--">-OR-<div class="g-signin2" data-onsuccess="onSignIn"></div>
        <label><span class="rem_me"><input name="login_remember" id="login_remember" type="checkbox" value="1">Remember Me</span></label>
        <a href="<?php echo SITE; ?>registration" class="forg_pass">No account yet?</a><br clear="all" />
        <a href="<?php echo SITE; ?>password-restore" class="forg_pass">Forgot Password?</a> <br clear="all" />         
    </form>
</div>
</div>
