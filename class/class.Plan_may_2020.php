<?php
 
  class Plan
     {
       var $table = '';
       var $error = '';
       public function __construct($nametable='payments') {
          $this->table = $nametable; 
             $this->error = '';
       }
//       function Plan($nametable='payments')
//           { $this->table = $nametable; 
//             $this->error = '';
//           }                                                
//          
        function check_plan($userid) 
          { global $dbh; 
            $stmt = $tmp = $query = '';
            $aux = 0;
            $this->error = '';
            if (!empty($userid))
               { 
                   //permit users 10 days
                 $userQuery = "SELECT date_created FROM users WHERE id=? limit 1";
                 $stmt = $dbh->prepare($userQuery);                  
                 $stmt->bindValue(1, $userid, PDO::PARAM_INT);
                 $tmp = $stmt->execute();
                 //if ($tmp && $stmt->rowCount()>0){
                     $row = $stmt->fetch();
                      //foreach ($row as $col=>$value) {
                            $now = time(); // or your date as well
                            $your_date = strtotime($row['date_created']);
                            $datediff = $now - $your_date;
                            $days = round($datediff / (60 * 60 * 24));
                          //  return $now." - ".$your_date;
                            if($days >= 0 && $days <= 10){
                                return 1;
                            }
                      //}
                 //}
                 
                   //permit users 10 days
                 $userQuery = "SELECT date_freecycle FROM users WHERE id=? limit 1";
                 $stmt = $dbh->prepare($userQuery);                  
                 $stmt->bindValue(1, $userid, PDO::PARAM_INT);
                 $tmp = $stmt->execute();
                // return $row['date_freecycle'];
                 //if ($tmp && $stmt->rowCount()>0){
                     $row = $stmt->fetch();
                      //foreach ($row as $col=>$value) {
                            $now = time(); // or your date as well
                            $your_date = strtotime($row['date_freecycle']);
                            //die($your_date."----");
                            $datediff = $now - $your_date;
                            $days = round($datediff / (60 * 60 * 24));
                        //    return $now." - ".$your_date;
                            if($days >= 0 && $days <= 10){
                                return 1;
                            }
                      //}
                 //}
                   
                   
                   //$datenow = date("Y-m-d H:i:s");
                 $query = "SELECT * FROM ".$this->table." WHERE id_user=? AND (date_expire>=NOW() OR (plan_type='Case by Case' AND status!='close'))";
                 //$query = "SELECT * FROM ".$this->table." WHERE id_user=? AND (date_expire>=$datenow OR (plan_type='Case by Case' AND status!='close'))";
                 $stmt = $dbh->prepare($query);                  
                 $stmt->bindValue(1, $userid, PDO::PARAM_INT);
                 $tmp = $stmt->execute();
                 if ($tmp && $stmt->rowCount()>0)
                    { if ($this->get_total_credit($userid)>0)
                         $aux = 1;   
                    }     
               }
            return $aux;
            //return 1;
          }
          
        function get_active_plan($userid) 
          { global $dbh; 
            $stmt = $tmp = $query = '';
            $aux = array();
            $this->error = '';
            if (!empty($userid))
               { $query = "SELECT * FROM ".$this->table." WHERE id_user=? AND (date_expire>=NOW() OR (plan_type='Case by Case' AND status!='close'))";
                 $stmt = $dbh->prepare($query);                  
                 $stmt->bindValue(1, $userid, PDO::PARAM_INT);
                 $tmp = $stmt->execute();
                 if ($tmp && $stmt->rowCount()>0)
                    { $plans = $stmt->fetchAll(PDO::FETCH_OBJ);
                      foreach ($plans as $plan){
                         $aux[] = $plan->plan_type;
                         $aux[] = $plan->amount;
                         $aux[] = $plan->date_expire;
                      }
                    }
               }
            return $aux;
          }
          
        function get_total_credit($userid) 
          { global $dbh; 
            $stmt = $tmp = $query = '';
            $credit = 0;
            $this->error = '';
            if (!empty($userid))
               { $query = "SELECT * FROM ".$this->table." WHERE id_user=? AND (date_expire>=NOW() OR (plan_type='Case by Case' AND status!='close'))";
                 $stmt = $dbh->prepare($query);                  
                 $stmt->bindValue(1, $userid, PDO::PARAM_INT);
                 $tmp = $stmt->execute();
                 if ($tmp && $stmt->rowCount()>0)
                    { $plans = $stmt->fetchAll(PDO::FETCH_OBJ);
                      foreach ($plans as $plan){
                         $credit += $plan->amount;                        
                      }
                    }
               }
            return $credit;
          }     
          
        function change_status_plan($userid) // only for case by case
          { global $dbh; 
            $stmt = $stmtnew = $tmp = $query = '';
            $this->error = '';
            if (!empty($userid))
               { $query = "SELECT * FROM ".$this->table." WHERE id_user=? AND (date_expire>=NOW() OR (plan_type='Case by Case' AND status!='close'))";
                 $stmt = $dbh->prepare($query);                  
                 $stmt->bindValue(1, $userid, PDO::PARAM_INT);
                 $tmp = $stmt->execute();
                 if ($tmp && $stmt->rowCount()>0)
                    { $plans = $stmt->fetchAll(PDO::FETCH_OBJ);
                      foreach ($plans as $plan){
                         if ($plan->plan_type=='Case by Case')
                            { $query = "UPDATE ".$this->table." SET status = 'close' WHERE id_payment = ?";
                              $stmtnew = $dbh->prepare($query);                  
                              $stmtnew->bindValue(1, $plan->id_payment, PDO::PARAM_INT);
                              $tmp = $stmtnew->execute();
                            }
                       } 
                    }
               }
          }            
                    
     }
 
?>