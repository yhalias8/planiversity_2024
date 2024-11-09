<?php
$output_dir = "uploads/";
if(isset($_FILES["myfile"]))
{
 $ret = array();
 
// This is for custom errors; 
/* $custom_error= array();
 $custom_error['jquery-upload-file-error']="File already exists";
 echo json_encode($custom_error);
 die();
*/
 $error =$_FILES["myfile"]["error"];
 //You need to handle  both cases
 //If Any browser does not support serializing of multiple files using FormData() 
 
 // check if you already upload 4 document or not
 include('../config.ini.php');
 $stmt = $dbh->prepare("SELECT COUNT(*) FROM documents WHERE type=? AND id_user=?");                  
 $stmt->bindValue(1, $_REQUEST['type'], PDO::PARAM_STR);
 $stmt->bindValue(2, $_REQUEST['vt'], PDO::PARAM_INT);
 $tmp = $stmt->execute();
 if ($tmp && $stmt->rowCount()>=4)
    { $custom_error['jquery-upload-file-error']="File already exists";
      echo json_encode($custom_error);
    }
 else {
 
 if(!is_array($_FILES["myfile"]["name"])) //single file
 {  $fileName = $_REQUEST['tp'].'_'.$_REQUEST['type'].'_'.$_FILES["myfile"]["name"];
    if (move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName))
       { if (!empty($_REQUEST['vt'])) // add in documents table
            { $id_doc = $query = '';
              $query = "INSERT INTO documents (id_user, name, type) VALUES (?, ?, ?)";
              $stmt = $dbh->prepare($query);                  
              $stmt->bindValue(1, $_REQUEST['vt'], PDO::PARAM_INT);
              $stmt->bindValue(2, $fileName, PDO::PARAM_STR);
              $stmt->bindValue(3, $_REQUEST['type'], PDO::PARAM_STR);
              $stmt->execute();
              $id_doc = $dbh->lastInsertId();
              if (!empty($_REQUEST['tp']) && !strstr($_REQUEST['tp'],'u')) // add in trips-docs table
                 { $query = "INSERT INTO `trips-docs` (`id_trip`, `id_document`) VALUES (?, ?)";
                   $stmt = $dbh->prepare($query);                  
                   $stmt->bindValue(1, $_REQUEST['tp'], PDO::PARAM_INT);
                   $stmt->bindValue(2, $id_doc, PDO::PARAM_INT);
                   $stmt->execute();
                   $tmp = ''; $tmp = $stmt->errorInfo();
                 }
            }
         $ret[]= $fileName;         
       }                   
 }
 else  //Multiple files, file[]
 {
   $fileCount = count($_FILES["myfile"]["name"]);
   for($i=0; $i < $fileCount; $i++)
   {
    $fileName = $_REQUEST['trip'].'_'.$_REQUEST['type'].'_'.$_FILES["myfile"]["name"][$i];
    if (move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName))
       { if (!empty($_REQUEST['vt'])) // add in documents table
            { $id_doc = $query = '';
              $query = "INSERT INTO documents (id_user, name, type) VALUES (?, ?, ?)";
              $stmt = $dbh->prepare($query);                  
              $stmt->bindValue(1, $_REQUEST['vt'], PDO::PARAM_INT);
              $stmt->bindValue(2, $fileName, PDO::PARAM_STR);
              $stmt->bindValue(3, $_REQUEST['type'], PDO::PARAM_STR);
              $stmt->execute();
              $id_doc = $dbh->lastInsertId();
              if (!empty($_REQUEST['tp']) && !strstr($_REQUEST['tp'],'u')) // add in trips-docs table
                 { $query = "INSERT INTO `trips-docs` (`id_trip`, `id_document`) VALUES (?, ?)";
                   $stmt = $dbh->prepare($query);                  
                   $stmt->bindValue(1, $_REQUEST['tp'], PDO::PARAM_INT);
                   $stmt->bindValue(2, $id_doc, PDO::PARAM_INT);
                   $stmt->execute();                   
                 }
            }
         $ret[]= $fileName;
       }
   }
 
 }
 
 echo json_encode($ret);
 }
 }
?>