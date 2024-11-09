<?php
include_once("config.ini.php");
include_once("include_login_php.php");
include_once("process/email_process.php");
include_once("include_new_header.php");

$email="its.kraftbj@gmail.com";

?>

<script>
        
gr("track", "conversion", { email: "<?= $email ?>" });

alert("<?= $email ?>");
        
</script>


