<?php
include_once("config.ini.php");
include_once("include_login_php.php");
include('include_doctype.php');   
?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description"
        content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
<meta name="keywords" content="Consolidated Travel Information Management">
<meta name="author" content="">
<title>PLANIVERSITY - PASSWORD RESTORE</title>

<link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

<link href="<?php echo SITE; ?>style/menu.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />
<script src="<?php echo SITE; ?>js/responsive-nav.js"></script>

</head>

<body class="inner_page inner_page2">

<div class="content">

    <?php include('include_header.php') ?>
    
    <h2 class="style3">Welcome to Planiversity</h2>
    
<?php
$output = '';

if (isset($_POST['restore_submit']))
   {  $email = filter_var($_POST["restore_email"], FILTER_SANITIZE_STRING);
      $result = $auth->requestReset($email,true);
      $output = $result['message'];
   }
?>    

    <section class="main_cont">
        
        <div class="cont_in">
        
             <article class="cont_opc login_cont">
                  <form name="restore_form" method="POST">
                    <h1>Password Restore</h1>
                    <div class="error_style"><?php echo $output; ?></div>
                    <input name="restore_email" id="restore_email" maxlength="100" type="text" value="" class="inp1" placeholder="Email"><br />
                    <input name="restore_submit" id="restore_submit" type="submit" class="button button2" value="RESTORE">
                 </form>
             </article>
          
        </div>
        
    </section>
    
</div>    

    <footer class="footer"><?php include('include_footer.php'); ?></footer>

</body>
</html>