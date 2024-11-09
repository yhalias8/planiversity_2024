<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
  $_SESSION['redirect'] = 'my-profile';
  header("Location:" . SITE . "login");
}

$msg = '';
if (isset($_FILES['fileUp'])) {
  $_tmp = 'ajaxfiles/profile/';
  if (empty($_FILES['fileUp']['name'])) {
    $msg = 'Please choose a file!';
  } else {
    $allowed = array('jpg', 'jpeg', 'gif', 'png');
    $file_name = $_FILES['fileUp']['name'];
    $file_extn = strtolower(end(explode('.', $file_name)));
    $file_temp = $_FILES['fileUp']['tmp_name'];

    if (in_array($file_extn, $allowed)) {
      if (move_uploaded_file($file_temp, $_tmp . $file_name)) { // save in DB
        $query = "UPDATE users SET picture = ? WHERE id = ?";
        $stmtnew = $dbh->prepare($query);
        $stmtnew->bindValue(1, $file_name, PDO::PARAM_STR);
        $stmtnew->bindValue(2, $userdata['id'], PDO::PARAM_INT);
        $stmtnew->execute();
        $userdata['picture'] = $file_name;
      } else
        $msg = 'A system error has been encountered. Please try again.';
    } else {
      $msg = 'Incorrect file type. Allowed: ' . implode(', ', $allowed);
    }
  }
}

$output = '';
if (isset($_POST['myprofile_submit'])) {
  $curr_password = $_POST['myprofile_cpass'];
  $new_password = $_POST['myprofile_npass'];
  $confirm_password = $_POST['myprofile_nrpass'];
  $result = $auth->changePassword($userdata['id'], $curr_password, $new_password, $confirm_password);
  $output = $result['message'];
}

include('include_doctype.php');
?>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
  <meta name="keywords" content="Consolidated Travel Information Management">
  <meta name="author" content="">
  <title>PLANIVERSITY - MY PROFILE</title>

  <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
  <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

  <link href="<?php echo SITE; ?>style/menu.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />
  <script src="<?php echo SITE; ?>js/responsive-nav.js"></script>

  <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
  <script>
    var SITE = '<?php echo SITE; ?>'
  </script>
  <script src="<?php echo SITE; ?>js/my_profile.js"></script>

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146873572-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-146873572-1');
  </script>
  <!-- Google Tag Manager -->
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-PBF3Z2D');
  </script>
  <!-- End Google Tag Manager -->

</head>

<body class="inner_page inner_page2">
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <div class="content">

    <?php include('include_header.php') ?>

    <h2 class="style3">Welcome to Planiversity</h2>

    <section class="main_cont">

      <div class="cont_in">

        <article class="cont_opc login_cont my_acc_cont1">
          <form name="myprofile_form" id="myprofile_form" method="post">
            <h1>My Account</h1>
            <div class="my_acc_cont2">
              Name : <?php echo $userdata['name']; ?><br />
              Account Type : <?php echo $userdata['account_type']; ?><br />
              Email : <?php echo $userdata['email']; ?><br />
              Customer Number : <?php echo $userdata['customer_number']; ?><br />
              Active Plan :
              <?php
              include("class/class.Plan.php");
              $plan = new Plan();
              $_tmp = $plan->get_active_plan($userdata['id']);
              if ($_tmp)
                echo $_tmp[0] . ' - Expiration date : ' . date('m-d-Y', strtotime($_tmp[2]));
              else
                echo 'None';
              ?>
              <br />

            </div>

            <div class="error_style"><?php echo $output; ?></div>
            <input id="myprofile_cpass" name="myprofile_cpass" type="password" value="" class="inp1" maxlength="50" placeholder="Current Password"><br />
            <input id="myprofile_npass" name="myprofile_npass" type="password" value="" class="inp1" maxlength="50" placeholder="New Password"><br />
            <input id="myprofile_nrpass" name="myprofile_nrpass" type="password" value="" class="inp1" maxlength="50" placeholder="Repeat New Password"><br />
            <input id="myprofile_submit" name="myprofile_submit" type="submit" class="button button2" value="Submit">
          </form>
        </article>

        <article class="cont_opc login_cont my_prof_r">
          <?php
          $img = 'images/my_profile_icon.png';
          if ($userdata['picture']) $img = 'ajaxfiles/profile/' . $userdata['picture'];
          ?>
          <form id="formId" name="formId" action="" method="post" enctype="multipart/form-data">
            <a onClick="document.getElementById('fileUp').click(); return false"><img class="img" src="<?php echo SITE; ?><?php echo $img; ?>"></a>
            <br /><img src="<?php echo SITE; ?>images/icon_photo.png" alt="Click to change picture" class="icon_photo" />
            <div class="error_style"><?php echo $msg; ?></div>
            <input type="file" name="fileUp" id="fileUp" style="display: none;" accept="image/*">
          </form>
          <script type="">
            $("#fileUp").on("change", function() {
                      $("#formId").submit();
                  });
                  </script>
          <div class="my_prof_r_in">
            <h2><?php echo $userdata['name']; ?><br /><span>$<?php if ($_tmp[1]) echo $_tmp[1];
                                                              else echo '0.00'; ?></span></h2>
            <h3>Documents</h3>
            <ul>
              <li>Passport
                <?php
                $stmt = $dbh->prepare("SELECT * FROM documents WHERE type='passport' AND id_user=?");
                $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                $tmp = $stmt->execute();
                if ($tmp && $stmt->rowCount() > 0) {
                  $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
                  foreach ($documents as $document) {
                    echo '<document id="doc_' . $document->id_document . '">
                                          <a onclick="del_element(' . $document->id_document . ')"><img src="' . SITE . 'images/delete.png" alt="" title="Delete" /></a>
                                          <a target="_blank" href="' . SITE . 'ajaxfiles/uploads/' . $document->name . '"><img src="' . SITE . 'images/document_icon.png" alt="" title="' . $document->name . '" class="doc" /></a>
                                          </document>';
                  }
                }
                ?>
              </li>
              <li>Driver's License
                <?php
                $stmt = $dbh->prepare("SELECT * FROM documents WHERE type='driver' AND id_user=?");
                $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                $tmp = $stmt->execute();
                if ($tmp && $stmt->rowCount() > 0) {
                  $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
                  foreach ($documents as $document) {
                    echo '<document id="doc_' . $document->id_document . '">
                                          <a onclick="del_element(' . $document->id_document . ')"><img src="' . SITE . 'images/delete.png" alt="" title="Delete" /></a>
                                          <a target="_blank" href="' . SITE . 'ajaxfiles/uploads/' . $document->name . '"><img src="' . SITE . 'images/document_icon.png" alt="" title="' . $document->name . '" class="doc" /></a>
                                          </document>';
                  }
                }
                ?>
              </li>
              <li>Hotel Itinerary
                <?php
                $stmt = $dbh->prepare("SELECT * FROM documents WHERE type='hitinerary' AND id_user=?");
                $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                $tmp = $stmt->execute();
                if ($tmp && $stmt->rowCount() > 0) {
                  $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
                  foreach ($documents as $document) {
                    echo '<document id="doc_' . $document->id_document . '">
                                          <a onclick="del_element(' . $document->id_document . ')"><img src="' . SITE . 'images/delete.png" alt="" title="Delete" /></a>
                                          <a target="_blank" href="' . SITE . 'ajaxfiles/uploads/' . $document->name . '"><img src="' . SITE . 'images/document_icon.png" alt="" title="' . $document->name . '" class="doc" /></a>
                                          </document>';
                  }
                }
                ?>
              </li>
              <li>Flight Itinerary
                <?php
                $stmt = $dbh->prepare("SELECT * FROM documents WHERE type='fitinerary' AND id_user=?");
                $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                $tmp = $stmt->execute();
                if ($tmp && $stmt->rowCount() > 0) {
                  $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
                  foreach ($documents as $document) {
                    echo '<document id="doc_' . $document->id_document . '">
                                          <a onclick="del_element(' . $document->id_document . ')"><img src="' . SITE . 'images/delete.png" alt="" title="Delete" /></a>
                                          <a target="_blank" href="' . SITE . 'ajaxfiles/uploads/' . $document->name . '"><img src="' . SITE . 'images/document_icon.png" alt="" title="' . $document->name . '" class="doc" /></a>
                                          </document>';
                  }
                }
                ?>
              </li>
            </ul>
          </div>
        </article>

      </div>

    </section>

    <br clear="all" />

  </div>

  <footer class="footer">
    <div class="cont_in style6">
      <span class="phone">Your Customer Number: <strong><?php echo $userdata['customer_number'] ?></strong></span>
      <a href="<?php echo SITE; ?>trip/how-are-you-traveling" class="button bt_blue go_org">Go to Trip Organizer</a><br clear="all" />
    </div>
    <?php include('include_footer.php'); ?>
  </footer>

</body>

</html>