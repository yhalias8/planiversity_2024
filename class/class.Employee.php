<?php
 
  class Employee
     {
       var $table = '';
       var $error = '';
      
       function Employee($nametable='employees')
           { $this->table = $nametable; 
             $this->error = '';
           }                                     
           
       function put_data($iduser,$fname,$lname,$employeeid,$address,$city,$state,$zip_code,$phone,$driver_number,$driver_state,$driver_expiration,$social_number,$birthdate,$email,$gender,$race,$veteran,$doc1,$doc2,$doc3,$doc4) 
          { global $dbh; 
            $stmt = $tmp = $query = '';
            $this->error = '';
            if (!empty($iduser) && !empty($fname) && !empty($lname) && !empty($employeeid))
               { $query = "INSERT INTO ".$this->table." (id_user, f_name, l_name, employee_id, address, city, state, zip_code, phone, driver_number, driver_state, driver_expiration, social_number, birthdate, email, gender, race, veteran, document1, document2, document3, document4) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                 $stmt = $dbh->prepare($query);                  
                 $stmt->bindValue(1, $iduser, PDO::PARAM_INT);
                 $stmt->bindValue(2, $fname, PDO::PARAM_STR);
                 $stmt->bindValue(3, $lname, PDO::PARAM_STR);
                 $stmt->bindValue(4, $employeeid, PDO::PARAM_STR);
                 $stmt->bindValue(5, $address, PDO::PARAM_STR);
                 $stmt->bindValue(6, $city, PDO::PARAM_STR);
                 $stmt->bindValue(7, $state, PDO::PARAM_STR);
                 $stmt->bindValue(8, $zip_code, PDO::PARAM_STR);
                 $stmt->bindValue(9, $phone, PDO::PARAM_STR);
                 $stmt->bindValue(10, $driver_number, PDO::PARAM_STR);
                 $stmt->bindValue(11, $driver_state, PDO::PARAM_STR);
                 $stmt->bindValue(12, $driver_expiration, PDO::PARAM_STR);
                 $stmt->bindValue(13, $social_number, PDO::PARAM_STR);
                 $stmt->bindValue(14, $birthdate, PDO::PARAM_STR);
                 $stmt->bindValue(15, $email, PDO::PARAM_STR);
                 $stmt->bindValue(16, $gender, PDO::PARAM_STR);
                 $stmt->bindValue(17, $race, PDO::PARAM_STR);
                 $stmt->bindValue(18, $veteran, PDO::PARAM_STR);
                 $stmt->bindValue(19, $doc1, PDO::PARAM_STR);
                 $stmt->bindValue(20, $doc2, PDO::PARAM_STR);
                 $stmt->bindValue(21, $doc3, PDO::PARAM_STR);
                 $stmt->bindValue(22, $doc4, PDO::PARAM_STR);
                 $tmp = $stmt->execute();
                 if (!$tmp)
                     $this->error = 'error_fail';  
                     /*{ $tt = $stmt->errorInfo(); $this->error = $tt[2]; }*/
               }
            else
               $this->error = 'error_mising'; 
          }          
          
       function del_data($id)
          { global $dbh;
            $tmp = ''; 
            $query = "DELETE FROM ".$this->table." WHERE id_employee = ?";
            $stmt = $dbh->prepare($query);                  
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if (!$tmp)
                $this->error = 'error_fail';
            /*else // delete al uploaded documents
               ( if (file_exists($filePath)) unlink($filePath);
               )*/                
          }           
     }
 
?>