<header class="admin_header">
    
    <div class="admin_cont_in">
        
        <a href="<?php echo SITE; ?>welcome"><img src="<?php echo SITE; ?>images/inner_logo.png" class="logo" alt="" /></a>
        <nav class="nav-collapse">
        <?PHP 
        $menu_user;$menu_routes;$menu_settings;$menu_transactions ='';
          if(strstr($_SERVER['SCRIPT_FILENAME'],'adminpanel_users.php')) $menu_user ='class="active"';
          if(strstr($_SERVER['SCRIPT_FILENAME'],'adminpanel_settings.php')) $menu_settings ='class="active"';
          if(strstr($_SERVER['SCRIPT_FILENAME'],'adminpanel_routes.php')) $menu_routes ='class="active"';
          if(strstr($_SERVER['SCRIPT_FILENAME'],'adminpanel_transactions.php')) $menu_transactions ='class="active"';
              
          
        ?>
              <ul class="opt_l">
                  <li><a href="<?php echo SITE; ?>welcome">Home</a></li>
                  <li><a href="<?php echo SITE; ?>apanel/routes" <?php echo $menu_routes; ?>>Routes</a></li>
                  <li><a href="<?php echo SITE; ?>apanel/settings" <?php echo $menu_settings; ?>>Settings</a></li>
                  <!--<li><a href="#">Subway Maps</a></li>-->
                  <li><a href="<?php echo SITE; ?>apanel/transactions" <?php echo $menu_transactions; ?>>Transactions</a></li>
                  <li><a href="<?php echo SITE; ?>apanel/users" <?php echo $menu_user; ?>>Users</a></li>
              </ul>
              <ul class="opt_r">
                 <?php
                    $img = 'images/img3.png';
                    if ($userdata['picture']) $img = 'ajaxfiles/profile/'.$userdata['picture'];
                  ?>
                 <li class="user"><a href="<?php echo SITE; ?>my-profile"><span><img src="<?php echo SITE; ?>/<?php echo $img; ?>" alt="" /></span><br /><?php echo $userdata['name']; ?></a></li> 
                 <!--<li><a href="<?php echo SITE; ?>welcome" class="link1"><?php echo $userdata['name']; ?></a></li>-->
                 <li><a href="<?php echo SITE; ?>logout" class="link2">Log Out</a></li>
              </ul>
         </nav>
        <script>
          var navigation = responsiveNav(".nav-collapse");
        </script>
        
    </div>    
    
</header>