<?php
/**
 * @author: Fabian Rolof <fabian@rolof.pl>
 */

include_once (__DIR__ . "/../ajaxfiles/list_process.php");
include_once(__DIR__ . '/class.TripPlan.php');

class UpdateStatus
{
    public function hasAccessToTrip($tripId, $userId)
    {
        global $dbh;

        $sql = "select count(id_trip) as num from trips where id_trip= :id_trip and id_user= :id_user";

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue("id_trip", $tripId, \PDO::PARAM_INT);
        $stmt->bindValue("id_user", $userId, \PDO::PARAM_INT);
        $tmp = $stmt->execute();
        if (!$tmp) {
            return false;
        }

        $item = $stmt->fetch(\PDO::FETCH_OBJ);
        if ($item->num > 0) {
            return true;
        }

        $sql = <<<SQL
select count(`users`.id) as num
from `connect_details` 
    inner join `connect_master` on `connect_details`.`connect_id` = `connect_master`.`id` 
    inner join `employees` on `connect_details`.`people_id` = `employees`.`id_employee` 
    left join `travel_groups` on `connect_master`.`group_id` = `travel_groups`.`id` 
    inner join `users` on `users`.`customer_number` = `employees`.`employee_id` 
where `connect_master`.`id_trip` = :id_trip and `users`.`id` = :id_user

SQL;

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue("id_trip", $tripId, \PDO::PARAM_INT);
        $stmt->bindValue("id_user", $userId, \PDO::PARAM_INT);
        $tmp = $stmt->execute();
        if (!$tmp) {
            return false;
        }

        $item = $stmt->fetch(\PDO::FETCH_OBJ);

        if ($item->num > 0) {
            return true;
        }

        return false;
    }

    public function store($tripId, $userId, $updateStatus, $for, $people)
    {
        global $dbh, $auth;

        if (!in_array($for,['all', 'selected'])) {
            return false;
        }

        if ($for == 'selected' && count($people) == 0) {
            die("people");
            return false;
        }

        if (trim($updateStatus) == "") {
            die("status");
            return false;
        }

        foreach ($people as $person) {
            if (!$this->hasAccessToTrip($tripId, $person)) {
                die("ziom" . $person);
                return false;
            }
        }
        $updateStatus = trim(htmlentities($updateStatus));

        $sql = <<<SQL
        insert into update_status (trip_id, `for`, user_id, `status`) values (:trip_id, :for, :user_id, :status);
SQL;
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":trip_id", $tripId);
        $stmt->bindParam(":for", $for);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":status", $updateStatus);
        $stmt->execute();

        if ($for == 'selected') {
            $updateStatusId = $dbh->lastInsertId();

            foreach ($people as $person) {
                $this->addPersonToUpdateStatus($updateStatusId, $person);
            }
            $this->addPersonToUpdateStatus($updateStatusId, $userId);
        }
        $this->sendNotification($tripId, $userId, $updateStatus, $for, $people);

        return true;
    }

    private function addPersonToUpdateStatus($updateStatusId, $userId)
    {
        global $dbh;
        $sql = "insert into update_status_users (update_status_id, user_id) values (:update_status_id, :user_id)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":update_status_id", $updateStatusId);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
    }

    private function sendNotification($tripId, $userId, $updateStatus, $for, $people)
    {
        if ($for == 'selected') {
            $listOfUsers = $people;
        } else {
            $listOfUsers = $this->getAllowedPeople($tripId, $userId);
        }

        $trip = new TripPlan();
        $trip->get_data($tripId);


        foreach ($listOfUsers as $user) {
            $data = [
                "UserId" => $user,
                "NotificationTitle" => "Status Update",
                "NotificationBody" => notificationBodyProcess("status_update", "Status update", $trip->trip_title)
            ];
            $fields = json_encode($data);

            $mData = curlRequestPost($API_URL, $TOKEN, $fields);

        }
    }

    private function getAllowedPeople($tripId, $userId)
    {
        $trip = new TripPlan();
        $people = $trip->getPeopleRelatedToTrip($tripId);
        $ret = [];
        foreach ($people as $person) {
            if ($person->id != $userId) {
                $ret[] = $person->id;
            }
        }
        return $ret;
    }
}