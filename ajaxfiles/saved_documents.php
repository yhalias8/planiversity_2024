<?php
  
  include '../config.ini.php'; 

  if (!$auth->isLogged()) {
      $res['txt'] = '';
      $res['error'] = 'You do not have access to this function';
      echo json_encode($res);
      exit;
  }
  
  $stmt = $dbh->prepare("SELECT * FROM documents WHERE id_user=? AND type=? AND id_document NOT IN (SELECT id_document FROM `trips-docs`)");                  
  $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
  $stmt->bindValue(2, $_POST['type'], PDO::PARAM_STR);
  $tmp = $stmt->execute();
  $aux = '';
  if ($tmp && $stmt->rowCount()>0)
     { $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
       foreach ($documents as $document){
           $aux .= '<div id="doc_'.$document->id_document.'">
                       '.$document->name.'<br />
                       <div id="errordocuse_'.$document->id_document.'"></div>
                       <div id="docuse_'.$document->id_document.'" >use this document? <a onclick="savedoctrip('.$document->id_document.','.$_POST['tp'].')">yes</a> <a onclick="$(\'#doc_'.$document->id_document.'\').hide(\'fast\')">no</a></div>
                    </div>';
       }
       $res['txt'] = $aux;
       $res['error'] = '';
       echo json_encode($res);
     }
  else
     { $res['txt'] = '';
       $res['error'] = 'You don\'t have saved documents';
       echo json_encode($res);
     }       
?>
