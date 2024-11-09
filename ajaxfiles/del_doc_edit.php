<?php
$output_dir = "uploads/";
include('../config.ini.php');
if($_REQUEST["table"] == 'notes')
{
   // del document in DB
   //include('../config.ini.php');
   $query = "DELETE FROM notes WHERE id_note = ?";
   $stmt = $dbh->prepare($query);                  
   $stmt->bindValue(1, str_replace("..",".",$_REQUEST['id']), PDO::PARAM_INT);
   $stmt->execute();
   //$tmp = $stmt->errorInfo();
  // print_r($tmp);
	echo "Note deleted successfully!";
}
if($_REQUEST["table"] == 'timeline')
{
   // del document in DB
   //include('../config.ini.php');
   $query = "DELETE FROM timeline WHERE id_timeline = ?";
   $stmt = $dbh->prepare($query);                  
   $stmt->bindValue(1, str_replace("..",".",$_REQUEST['id']), PDO::PARAM_INT);
   $stmt->execute();
   /*$tmp = $stmt->errorInfo();*/
	echo "Reminder deleted successfully!";
}
if($_REQUEST["table"] == 'documents')
{
   // del document in DB
   $query = "DELETE FROM documents WHERE id_document = ?";
   $stmt = $dbh->prepare($query);                  
   $stmt->bindValue(1, str_replace("..",".",$_REQUEST['id']), PDO::PARAM_INT);
   $stmt->execute();
   /*$tmp = $stmt->errorInfo();*/
	echo "Document deleted successfully!";
}

?>