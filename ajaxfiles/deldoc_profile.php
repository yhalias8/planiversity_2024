<?php

include '../config.ini.php'; 
$output_dir = "uploads/";

if (!$auth->isLogged()) {
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}

$res['error'] = '';

if (isset($_POST['id']) && !empty($_POST['id']))
   { $query = "SELECT name FROM documents WHERE id_document = ?";
     $stmt = $dbh->prepare($query);                  
     $stmt->bindValue(1, $_POST['id'], PDO::PARAM_INT);
     $tmp = $stmt->execute();
     $_aux = '';
     if ($tmp && $stmt->rowCount()>0)
        { $docs = $stmt->fetchAll(PDO::FETCH_OBJ);
          foreach ($docs as $doc){
             $_aux = $doc->name; 
          }
        }  
    
     $fileName = $_aux;
     $filePath = $output_dir. $fileName;
     if (file_exists($filePath)) 
        { unlink($filePath);
          
          // del document in DB
          $query = "DELETE FROM documents WHERE id_document = ?";
          $stmt = $dbh->prepare($query);                  
          $stmt->bindValue(1, $_POST['id'], PDO::PARAM_INT);
          $stmt->execute();   
        }
     else
       { $res['error'] = 'A system error has been encountered. Please try again.';
         echo json_encode($res);
       }
     
     echo json_encode($res);
   }
?>
