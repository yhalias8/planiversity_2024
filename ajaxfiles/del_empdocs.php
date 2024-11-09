<?php
$output_dir = "documents/";
if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
{
	$fileName =$_POST['name'];
	$fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files	
	$filePath = $output_dir. $fileName;
	if (file_exists($filePath)) 
	{ unlink($filePath);
   /*$tmp = $stmt->errorInfo();
   $custom_error= array();
   $custom_error['jquery-upload-file-error']="---".$tmp[2];
   echo json_encode($custom_error);*/
 }
	echo "Deleted File ".$fileName."<br>";
}

?>