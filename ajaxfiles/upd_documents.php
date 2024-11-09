<?php
include("./list_process.php");

$output_dir = "uploads/";
if(isset($_REQUEST["orn"]))
{
	$fileName =$_REQUEST['filenm'];
	$fileOrientation =$_REQUEST['orn'];
	
   
       // del document in DB
       include('../config.ini.php');
       $query = "UPDATE documents SET orientation = ? WHERE name = ?";
       $stmt = $dbh->prepare($query);                  
       $stmt->bindValue(1, $fileOrientation, PDO::PARAM_STR);
       $stmt->bindValue(2, str_replace("..",".",$fileName), PDO::PARAM_STR);
       $stmt->execute();
       /*$tmp = $stmt->errorInfo();
       $custom_error= array();
       $custom_error['jquery-upload-file-error']="---".$tmp[2];
       echo json_encode($custom_error);*/
       return true;
}
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
 
    $trip_generated = $_REQUEST['trip_generated'];
    $trip_u_id = $_REQUEST['trip_u_id'];
    $trip_title = $_REQUEST['trip_title'];
    $type = $_REQUEST['type'];
 
 //You need to handle  both cases
 //If Any browser does not support serializing of multiple files using FormData() 
 if(!is_array($_FILES["myfile"]["name"])) //single file
 {  
    $fileName = $_REQUEST['tp'].'_'.$_REQUEST['type'].'_'.$_FILES["myfile"]["name"];
    if (move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName))
       { if (!empty($_REQUEST['vt'])) // add in documents table
            { include('../config.ini.php');
              $id_doc = $query = '';
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
                 
                 if ($trip_generated == 1) {

                    $data = [
                        "UserId" => $trip_u_id,
                        "NotificationTitle" => "New Document Added",
                        "NotificationBody" => notificationBodyProcess("add", "$type document", $trip_title)
                    ];
                    ActivityLogger::log($_REQUEST['tp'], ActivityLogger::DOCUMENT_ADDED);

                    $fields = json_encode($data);

                    $mData = curlRequestPost($API_URL, $TOKEN, $fields);

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
            { include('../config.ini.php');
              $id_doc = $query = '';
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
                 
                if ($trip_generated == 1) {
                    ActivityLogger::log($_REQUEST['tp'], ActivityLogger::DOCUMENT_ADDED);
                    $data = [
                        "UserId" => $trip_u_id,
                        "NotificationTitle" => "New Document Added",
                        "NotificationBody" => notificationBodyProcess("add", "$type document", $trip_title)
                    ];
                    $fields = json_encode($data);

                    $mData = curlRequestPost($API_URL, $TOKEN, $fields);

                }                 
            }
         $ret[]= $fileName;
       }
   }
 
 }
    echo json_encode($ret);
 }
 ?>