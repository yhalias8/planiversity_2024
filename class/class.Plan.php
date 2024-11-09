<?php

class Plan {

    var $table = '';
    var $error = '';

    public function __construct($nametable = 'payments') {
        $this->table = $nametable;
        $this->error = '';
    }

    // Business user
    function check_plan($userid) {
        global $dbh;
        $stmt = $tmp = $query = '';
        $aux = 0;
        $this->error = '';
        if (!empty($userid)) {
            $query = "SELECT * FROM " . $this->table . " WHERE id_user = ? AND date_expire >= NOW() AND status='succeeded' ORDER BY id_payment DESC LIMIT 1";
            
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $userid, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if ($tmp && $stmt->rowCount() > 0) {
                if ($this->get_total_credit($userid) > 0)
                    $aux = 1;
            }
        }
        return $aux;
    }

    function individual_check_plan($userid) {
        global $dbh;
        $stmt = $tmp = $query = '';
        $aux = 0;
        $this->error = '';

        if (!empty($userid)) {
            $query = "SELECT * FROM " . $this->table . " WHERE id_user=? AND (date_expire>=NOW() AND status='succeeded')";
            
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $userid, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if ($tmp && $stmt->rowCount() > 0) {
                if ($this->get_total_credit($userid) > 0)
                    $aux = 1;
            }
        }
        return $aux;
    }

    function get_active_plan($userid) {
        global $dbh;
        $stmt = $tmp = $query = '';
        $aux = array();
        $this->error = '';
        if (!empty($userid)) {
            $query = "SELECT * FROM " . $this->table . " WHERE id_user=? AND (date_expire>=NOW() OR (plan_type='Case by Case' AND status!='close'))";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $userid, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if ($tmp && $stmt->rowCount() > 0) {
                $plans = $stmt->fetchAll(PDO::FETCH_OBJ);
                foreach ($plans as $plan) {
                    $aux[] = $plan->plan_type;
                    $aux[] = $plan->amount;
                    $aux[] = $plan->date_expire;
                }
            }
        }
        return $aux;
    }


    function get_current_plan($userid) {
        global $dbh;
        $stmt = $tmp = $queryget_current_plan = '';
        $aux = array();
        $this->error = '';
        $plans=[];
        if (!empty($userid)) {
            $query = "SELECT plan_type,amount,DATE_FORMAT(date_expire, '%d-%m-%Y') as date_expire  FROM " . $this->table . " WHERE id_user=? AND (date_expire>=NOW() AND status='succeeded') ORDER BY id_payment DESC LIMIT 1";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $userid, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if ($tmp && $stmt->rowCount() > 0) {
                $plans = $stmt->fetch(PDO::FETCH_OBJ);                
            }
        }
        return $plans;
    }    



    function get_total_credit($userid) {
        global $dbh;
        $stmt = $tmp = $query = '';
        $credit = 0;
        $this->error = '';
        if (!empty($userid)) {
            $query = "SELECT * FROM " . $this->table . " WHERE id_user=? AND (date_expire>=NOW() AND status='succeeded')";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $userid, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if ($tmp && $stmt->rowCount() > 0) {
                $plans = $stmt->fetchAll(PDO::FETCH_OBJ);
                foreach ($plans as $plan) {
                    $credit += $plan->amount;
                }
            }
        }
        return $credit;
    }

    function change_status_plan($userid) { // only for case by case
        global $dbh;
        $stmt = $stmtnew = $tmp = $query = '';
        $this->error = '';
        if (!empty($userid)) {
            $query = "SELECT * FROM " . $this->table . " WHERE id_user=? AND (date_expire>=NOW() OR (plan_type='Case by Case' AND status!='close'))";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $userid, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if ($tmp && $stmt->rowCount() > 0) {
                $plans = $stmt->fetchAll(PDO::FETCH_OBJ);
                foreach ($plans as $plan) {
                    if ($plan->plan_type == 'Case by Case') {
                        $query = "UPDATE " . $this->table . " SET status = 'close' WHERE id_payment = ?";
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