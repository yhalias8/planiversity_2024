<?php
$output_dir = "documents/";
if(isset($_FILES["myfile"]))
{
 $ret = array();
 
// This is for custom errors; 
 $error = $_FILES["myfile"]["error"];
 //You need to handle  both cases
 //If Any browser does not support serializing of multiple files using FormData() 
 if(!is_array($_FILES["myfile"]["name"])) //single file
 {  
    $fileName = $_FILES["myfile"]["name"];
    if (!empty($_POST['name'])) $fileName = $_POST['name'].strstr($_FILES["myfile"]["name"],'.');
    if (move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName))
       { $ret[]= $fileName;         
       }                   
 }
 
 echo json_encode($ret); 
   
 //$custom_error['jquery-upload-file-error']="---".$_POST['name'];
 //echo json_encode($custom_error);
 }
 ?>