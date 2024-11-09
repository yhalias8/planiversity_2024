<?php
 
  class Job
     {
       var $table = '';
       var $error = '';
      
       function Job($nametable='jobs')
           { $this->table = $nametable; 
             $this->error = '';
           }
           
       function get_employee_list($idjob)
           { global $dbh; 
             $stmt = $tmp = $aux = $query = '';
             $this->error = '';
             
             $stmt = $dbh->prepare("SELECT f_name,l_name FROM `employee-job` as ej, employees as em WHERE ej.id_employee=em.id_employee AND id_job=?");                  
             $stmt->bindValue(1, $idjob, PDO::PARAM_INT);
             $tmp = $stmt->execute();
             $aux = '';
             if ($tmp && $stmt->rowCount()>0)
                { $emjobs = $stmt->fetchAll(PDO::FETCH_OBJ);
                  foreach ($emjobs as $emjob){
                     $aux .= ' -> '.$emjob->f_name.' '.$emjob->l_name;
                  }
                }
             //$tt = $stmt->errorInfo(); return $tt[2];                 
             return $aux;
           }                                         
           
       function put_data($iduser,$name,$category,$details,$cnumber,$address,$city,$state,$zcode,$doc1,$doc2,$doc3,$doc4,$doc5,$doc6,$doc7,$doc8) 
          { global $dbh; 
            $stmt = $tmp = $query = '';
            $this->error = '';
            if (!empty($iduser) && !empty($name))
               { $query = "INSERT INTO ".$this->table." (`id_user`, `name`, `category`, `details`, `contact_number`, `address`, `city`, `state`, `zip_code`, `document1`, `document2`, `document3`, `document4`, `equipment1`, `equipment2`, `equipment3`, `equipment4`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                 $stmt = $dbh->prepare($query);                  
                 $stmt->bindValue(1, $iduser, PDO::PARAM_INT);
                 $stmt->bindValue(2, $name, PDO::PARAM_STR);
                 $stmt->bindValue(3, $category, PDO::PARAM_STR);
                 $stmt->bindValue(4, $details, PDO::PARAM_STR);
                 $stmt->bindValue(5, $cnumber, PDO::PARAM_STR);
                 $stmt->bindValue(6, $address, PDO::PARAM_STR);
                 $stmt->bindValue(7, $city, PDO::PARAM_STR);
                 $stmt->bindValue(8, $state, PDO::PARAM_STR);
                 $stmt->bindValue(9, $zcode, PDO::PARAM_STR);
                 $stmt->bindValue(10, $doc1, PDO::PARAM_STR);
                 $stmt->bindValue(11, $doc2, PDO::PARAM_STR);
                 $stmt->bindValue(12, $doc3, PDO::PARAM_STR);
                 $stmt->bindValue(13, $doc4, PDO::PARAM_STR);
                 $stmt->bindValue(14, $doc5, PDO::PARAM_STR);
                 $stmt->bindValue(15, $doc6, PDO::PARAM_STR);
                 $stmt->bindValue(16, $doc7, PDO::PARAM_STR);
                 $stmt->bindValue(17, $doc8, PDO::PARAM_STR);
                 $tmp = $stmt->execute();
                 if (!$tmp)
                     $this->error = 'error_fail';  
                     //{ $tt = $stmt->errorInfo(); $this->error = $tt[2]; }
               }
            else
               $this->error = 'error_mising'; 
          }
          
       function put_je($idjob,$employees) 
          { global $dbh; 
            $stmt = $tmp = $query = '';
            $this->error = '';
            if (!empty($employees))
               { foreach ($employees as $employee){
                     $query = "INSERT INTO `employee-job` (id_job, id_employee) VALUES (?, ?)";
                     $stmt = $dbh->prepare($query);                  
                     $stmt->bindValue(1, $idjob, PDO::PARAM_INT);
                     $stmt->bindValue(2, $employee, PDO::PARAM_INT);
                     $tmp = $stmt->execute();                     
                 }                 
               }
            else
               $this->error = 'error_mising'; 
          }             
          
       function del_data($id)
          { global $dbh;
            $tmp = ''; 
            $query = "DELETE FROM ".$this->table." WHERE id_job = ?";
            $stmt = $dbh->prepare($query);                  
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if (!$tmp)
                $this->error = 'error_fail';            
          }           
     }
 
?>