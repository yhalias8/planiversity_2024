<?php

  include '../config.ini.php'; 

  if (!$auth->isLogged()) {
      $res['txt'] = '';
      $res['error'] = 'You do not have access to this function';
      echo json_encode($res);
      exit;
  }

  $error = '';
  if (!empty($_POST['dt']) && !empty($_POST['tp'])) // add in trips-docs table
     { $query = "INSERT INTO `trips-docs` (`id_trip`, `id_document`) VALUES (?, ?)";
       $stmt = $dbh->prepare($query);                  
       $stmt->bindValue(1, $_POST['tp'], PDO::PARAM_INT);
       $stmt->bindValue(2, $_POST['dt'], PDO::PARAM_INT);
       if (!$stmt->execute())
          $error = 'A system error has been encountered. Please try again.';
     }
     
     if($_POST['purpose'] == 'addComment'){
         $query = "INSERT INTO `user_comments` (`user_id`, `comment`) VALUES (?, ?)";
       $stmt = $dbh->prepare($query);                  
       $stmt->bindValue(1, $_POST['uid'], PDO::PARAM_INT);
       $stmt->bindValue(2, $_POST['comment'], PDO::PARAM_INT);
       if (!$stmt->execute())
          $error = 'A system error has been encountered. Please try again.';
        else
            $res['ins_ud'] = $dbh->lastInsertId();
            $res['ins_dt'] = date('M d, Y');
          
     }
     
     if($_POST['purpose'] == 'DelComment'){
         $query = "DELETE FROM `user_comments` WHERE id=?";
       $stmt = $dbh->prepare($query);                  
       $stmt->bindValue(1, $_POST['commid'], PDO::PARAM_INT);
       if (!$stmt->execute())
          $error = 'A system error has been encountered. Please try again.';
     }
     
     if($_POST['purpose'] == 'addEventComment'){
         $query = "INSERT INTO `events_comments` (`user_id`, `comment`) VALUES (?, ?)";
       $stmt = $dbh->prepare($query);                  
       $stmt->bindValue(1, $_POST['uid'], PDO::PARAM_INT);
       $stmt->bindValue(2, $_POST['comment'], PDO::PARAM_INT);
       if (!$stmt->execute())
          $error = 'A system error has been encountered. Please try again.';
        else
            $res['ins_ud'] = $dbh->lastInsertId();
            $res['ins_dt'] = date('M d, Y');
          
     }
     
     if($_POST['purpose'] == 'DelEventComment'){
         $query = "DELETE FROM `events_comments` WHERE id=?";
       $stmt = $dbh->prepare($query);                  
       $stmt->bindValue(1, $_POST['commid'], PDO::PARAM_INT);
       if (!$stmt->execute())
          $error = 'A system error has been encountered. Please try again.';
     }
  
  $res['error'] = $error;
  echo json_encode($res);

?>