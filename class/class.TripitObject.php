<?php


class TripitObject
{
    public $table;

    function __construct()
    {
        $this->table = 'tripit_objects';
    }

    function put_data($uid = 0, $tbr = null, $ue = null)
    {
        global $dbh;
        $query = "INSERT INTO " . $this->table . " (user_id,booking_site_conf_num,user_email) VALUES (?,?,?)";
        $dbh->prepare($query)->execute([$uid, $tbr, $ue]);
    }

    function edit_data($item)
    {
        global $dbh;
        $query = "UPDATE " . $this->table . " SET tripit_id=?,trip_id=?,is_client_traveler=?,relative_url=?,display_name=?,is_display_name_auto_generated=?,last_modified=?,booking_site_name=?,booking_site_phone=?,supplier_conf_num=?,supplier_name=?,supplier_phone=?,is_purchased = ?,total_cost=?,is_tripit_booking=? WHERE booking_site_conf_num=?";
        $stmt = $dbh->prepare($query);
        $stmt->execute([
            $item['id'],
            $item['trip_id'],
            $item['is_client_traveler'] == "true" ? 1 : 0,
            $item['relative_url'],
            $item['display_name'],
            $item['is_display_name_auto_generated'] == "true" ? 1 : 0,
            $item['last_modified'],
            $item['booking_site_name'],
            $item['booking_site_phone'],
            $item['supplier_conf_num'],
            $item['supplier_name'],
            $item['supplier_phone'],
            $item['is_purchased'] == "true" ? 1 : 0,
            $item['total_cost'],
            $item['is_tripit_booking'] == "true" ? 1 : 0,
            $item['booking_site_conf_num']
        ]);
        $obj = $this->get_data($item['id']);

        $user = $this->get_user_by_email($obj->user_email);
        if (isset($user)) {
            $query = "UPDATE " . $this->table . " SET user_id = ?  WHERE user_email = ?";
            $dbh->prepare($query)->execute([$user->id, $user->email]);
        }
    }

    function get_data($id)
    {
        global $dbh;

        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $dbh->prepare($query);
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return false;
    }

    function get_data_by_tripit_id($tid)
    {
        global $dbh;

        $query = "SELECT * FROM " . $this->table . " WHERE tripit_id = ?";
        $stmt = $dbh->prepare($query);
        $stmt->execute([$tid]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return false;
    }

    function get_user_by_email($ue)
    {
        global $dbh;

        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $dbh->prepare($query);
        $stmt->execute([$ue]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return false;
    }
}