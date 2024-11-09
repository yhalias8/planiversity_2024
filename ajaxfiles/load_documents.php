<?php
$dir="uploads";
$files = scandir($dir);

$ret= array();
$details = array();
foreach($files as $file)
{
	if($file == "." || $file == "..")
		continue;
	$filePath=$dir."/".$file;
 
 $tmp = $_REQUEST['tp'].'_'.$_REQUEST['type'].'_';
 
 if (strstr($file,$tmp)) {	
	$details['name']=$file;
	$details['path']=$filePath;
	$details['size']=filesize($filePath);
	$ret[] = $details;
 }

}

function multi_in_array($value, $array)
{   foreach ($array AS $item)
    {   if (!is_array($item))
        {   if ($item == $value)
            {   return true;
            }
            continue;
        }
        if (in_array($value, $item))
        {   return true;
        }
        else if (multi_in_array($value, $item))
        {   return true;
        }
    }
    return false;
} 

// load from DB
include '../config.ini.php'; 
$stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");                  
$stmt->bindValue(1, $_REQUEST['type'], PDO::PARAM_STR);
$stmt->bindValue(2, $_REQUEST['tp'], PDO::PARAM_INT);
$tmp = $stmt->execute();
if ($tmp && $stmt->rowCount()>0)
   { $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
     foreach ($documents as $document){
         if (!multi_in_array($document->name,$ret)) {
            $details['name']=$document->name;
            $details['path']=$dir."/".$document->name;
            $details['size']=filesize($dir."/".$document->name);
            $ret[] = $details;
         }          
     }     
   }

echo json_encode($ret);
?>