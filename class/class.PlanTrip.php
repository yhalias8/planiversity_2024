<?php

class PlanTrip
{
    var $id_plan = 0;
    var $trip_id = 0;
    var $plan_name = '';
    var $plan_type = '';
    var $plan_address = '';
    var $plan_checked_in = '';
    var $plan_date = '';
    var $plan_lat = '';
    var $plan_lng = '';
    var $schedule_linked = 0;
    var $schedule_id = '';
    var $schedule_flag = 0;
    var $created_at = '';
    var $table = '';
    var $error = '';
    var $lastid = '';

    function PlanTrip($nametable = 'tripit_plans')
    {
        $this->id_plan = 0;
        $this->trip_id = 0;
        $this->plan_name = '';
        $this->plan_type = '';
        $this->plan_address = '';
        $this->plan_checked_in = '';
        $this->plan_date = '';
        $this->plan_lat = '';
        $this->plan_lng = '';
        $this->schedule_linked = 0;
        $this->schedule_id = '';
        $this->schedule_flag = 0;
        $this->created_at = '';
        $this->table = $nametable;
        $this->error = '';
        $this->lastid = '';
    }

    function put_data($trip_id, $plan_name, $plan_type, $plan_address, $plan_checkin, $plan_date, $plan_lat, $plan_lng, $schedule_linked, $schedule_id, $schedule_flag = 0)
    {
        global $dbh;
        $stmt = $tmp = $query = '';
        $this->error = '';

        if (!empty($trip_id)) {
            //return $trip_id;
            $query = "INSERT INTO " . $this->table . " (trip_id, plan_name, plan_type, plan_address, plan_checked_in, plan_date, plan_lat,plan_lng,schedule_linked,schedule_id,schedule_flag,created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $trip_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $plan_name, PDO::PARAM_STR);
            $stmt->bindValue(3, $plan_type, PDO::PARAM_STR);
            $stmt->bindValue(4, $plan_address, PDO::PARAM_STR);
            $stmt->bindValue(5, $plan_checkin === 'true' ? 1 : 0, PDO::PARAM_INT);
            $stmt->bindValue(6, $plan_date, PDO::PARAM_STR);
            $stmt->bindValue(7, $plan_lat, PDO::PARAM_STR);
            $stmt->bindValue(8, $plan_lng, PDO::PARAM_STR);
            $stmt->bindValue(9, $schedule_linked, PDO::PARAM_INT);
            $stmt->bindValue(10, $schedule_id, PDO::PARAM_INT);
            $stmt->bindValue(11, $schedule_flag, PDO::PARAM_INT);
            $stmt->bindValue(12, date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $tmp = $stmt->execute();
            $this->lastid = $dbh->lastInsertId();
            // print_r($stmt->errorInfo());
            // die();
            if (!$tmp) {
                $this->error = 'error_fail';
            } else {
                ActivityLogger::log($trip_id, ActivityLogger::SUBPLAN_ADDED);
            }

        } else {
            $this->error = 'error_mising';
        }
    }

    function edit_data($id_plan, $plan_name, $plan_type, $plan_address, $plan_checked_in, $plan_date, $plan_lat, $plan_lng)
    {
        global $dbh;
        $stmt = $tmp = $query = '';
        $this->error = '';
        if (!empty($id_plan)) {
            $query = "UPDATE " . $this->table . " SET plan_name = ?, plan_type = ? ,plan_address = ?, plan_checked_in = ?, plan_date = ? ,plan_lat = ?,plan_lng = ? WHERE id_plan = ?";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $plan_name, PDO::PARAM_STR);
            $stmt->bindValue(2, $plan_type, PDO::PARAM_STR);
            $stmt->bindValue(3, $plan_address, PDO::PARAM_STR);
            $stmt->bindValue(4, $plan_checked_in === 'true' ? 1 : 0, PDO::PARAM_INT);
            $stmt->bindValue(5, $plan_date, PDO::PARAM_STR);
            $stmt->bindValue(6, $plan_lat, PDO::PARAM_STR);
            $stmt->bindValue(7, $plan_lng, PDO::PARAM_STR);
            $stmt->bindValue(8, $id_plan, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if (!$tmp) {
                $this->error = 'error_fail';
            } else {
                ActivityLogger::log($this->getTripId($id_plan), ActivityLogger::SUBPLAN_UPDATED);
            }

        } else
            $this->error = 'error_mising';
    }

    function modify_plan($id_plan, $plan_name)
    {
        global $dbh;
        $stmt = $tmp = $query = '';
        $this->error = '';
        if (!empty($id_plan)) {

            $query = "UPDATE " . $this->table . " SET plan_name = ? WHERE schedule_id = ?";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $plan_name, PDO::PARAM_STR);
            $stmt->bindValue(2, $id_plan, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if (!$tmp) {
                $this->error = 'error_fail';
            } else {
                $this->error = 'error_fail';
            }

        } else
            $this->error = 'error_mising';
    }

    function del_data($id)
    {
        global $dbh;
        $tmp = '';
        $tripId = $this->getTripId($id);
        $query = "DELETE FROM " . $this->table . " WHERE id_plan = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $tmp = $stmt->execute();
        if (!$tmp) {
            $this->error = 'error_fail';
        } else {
            ActivityLogger::log($tripId, ActivityLogger::SUBPLAN_DELETED);
        }

    }

    function del_schedule_data($id)
    {
        global $dbh;
        $tmp = '';
        $query = "select trip_id from {$this->table} where schedule_id = :schedule_id";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(":schedule_id", $id);
        $stmt->execute();
        $tripId = $stmt->fetchColumn();

        $query = "DELETE FROM " . $this->table . " WHERE schedule_id = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $tmp = $stmt->execute();
        if (!$tmp) {
            $this->error = 'error_fail';
        } else {
            ActivityLogger::log($tripId, ActivityLogger::SUBPLAN_DELETED);
        }

    }

    private function getTripId($id)
    {
        global $dbh;
        $query = "select trip_id from {$this->table} where id_plan = :id_plan";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(":id_plan", $id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
