<?php

class Timeline
{
    var $timeline_id = 0;
    var $trip_id = 0;
    var $timeline_title = '';
    var $timeline_date = '';
    var $plan_linked = 0;
    var $is_checked = 0;
    var $checked_in = 0;
    var $checked_in_date = '';
    var $note = '';
    var $document = '';
    var $table = '';
    var $error = '';
    var $lastid = '';

    function Timeline($nametable = 'timeline')
    {
        $this->timeline_id = 0;
        $this->trip_id = 0;
        $this->timeline_title = '';
        $this->timeline_date = '';
        $this->plan_linked = 0;
        $this->is_checked = 0;
        $this->checked_in = 0;
        $this->checked_in_date = '';
        $this->note = '';
        $this->document = '';
        $this->table = $nametable;
        $this->error = '';
        $this->lastid = '';
    }

    function get_data($timeline_id)
    {
        global $dbh;
        $stmt = $tmp = $query = '';
        $this->error = '';
        if (!empty($timeline_id)) {
            $query = "SELECT * FROM " . $this->table . " WHERE id_timeline = ?";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $timeline_id, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if (!$tmp)
                $this->error = 'error_fail';
            else {
                $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
                foreach ($timelines as $timeline) {
                    $this->timeline_id = $timeline_id;
                    $this->trip_id = $timeline->id_trip;
                    $this->timeline_title = $timeline->title;
                    $this->timeline_date = $timeline->date;
                    $this->plan_linked = $timeline->plan_linked;
                    $this->is_checked = $timeline->is_checked;
                    $this->checked_in = $timeline->checked_in;
                    $this->checked_in_date = $timeline->checked_in_date;
                    $this->note = $timeline->note;
                    $this->document = SITE . 'ajaxfiles/uploads/' . $timeline->document;
                }
            }
        } else
            $this->error = 'error_mising';
    }

    function put_data($trip_id, $title, $date, $plan_linked, $is_checked, $note = NULL, $document = NULL)
    {
        global $dbh;
        $stmt = $tmp = $query = '';
        $this->error = '';
        if (!empty($trip_id) && !empty($title) && !empty($date)) {
            $query = "INSERT INTO " . $this->table . " (id_trip,title,plan_linked,is_checked,note,document,date) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $trip_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $title, PDO::PARAM_STR);
            $stmt->bindValue(3, $plan_linked, PDO::PARAM_INT);
            $stmt->bindValue(4, $is_checked, PDO::PARAM_INT);
            $stmt->bindValue(5, $note, PDO::PARAM_STR);
            $stmt->bindValue(6, $document, PDO::PARAM_STR);
            $stmt->bindValue(7, $date, PDO::PARAM_STR);

            $stmt->execute();
            $id = $dbh->lastInsertId();
            ActivityLogger::log($trip_id, ActivityLogger::EVENT_ADDED);
            return $id;
        } else
            $this->error = 'error_mising';
    }

    function edit_data($timeline_id, $title, $date, $checkin = NULL, $note = NULL, $document = NULL)
    {
        global $dbh;
        $stmt = $tmp = $query = '';
        $this->error = '';

        if (!empty($timeline_id) && !empty($title) && !empty($date)) {
            $query = "UPDATE " . $this->table . " SET title = ?, date = ?";
            $params = [$title, $date];

            if (!is_null($checkin)) {
                $query .= ", is_checked = ?";
                $params[] = $checkin;
            }

            if (!is_null($note)) {
                if ($note) {
                    $query .= ", note = ?";
                    $params[] = $note;
                } else {
                    $query .= ", note = NULL";
                }
            }

            if (!is_null($document)) {
                if ($document) {
                    $query .= ", document = ?";
                    $params[] = $document;
                } else {
                    $query .= ", document = NULL";
                }
            }

            $query .= " WHERE id_timeline = ?";
            $params[] = $timeline_id;

            $stmt = $dbh->prepare($query);

            for ($i = 0; $i < count($params); $i++) {
                $param = $params[$i];
                if ($param === "true" || $param === "false") {
                    $param = $param === "true" ? 1 : 0;
                }
                $stmt->bindValue($i + 1, $param, is_int($param) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }

            $tmp = $stmt->execute();
            if (!$tmp) {
                $this->error = 'error_fail';
            } else {
                ActivityLogger::log($this->getTripId($timeline_id), ActivityLogger::EVENT_UPDATED);
            }
        } else {
            $this->error = 'error_missing';
        }
    }


    function checkin_process($timeline_id, $date)
    {
        global $dbh;
        $stmt = $tmp = $query = '';
        $this->error = '';
        if (!empty($timeline_id) && !empty($date)) {
            $query = "UPDATE " . $this->table . " SET checked_in = ?, checked_in_date = ? WHERE id_timeline = ? and is_checked = ?";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, 1, PDO::PARAM_INT);
            $stmt->bindValue(2, $date, PDO::PARAM_STR);
            $stmt->bindValue(3, $timeline_id, PDO::PARAM_INT);
            $stmt->bindValue(4, 0, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if (!$tmp) {
                $this->error = 'error_fail';
            } else {
                ActivityLogger::log($this->getTripId($timeline_id), ActivityLogger::EVENT_UPDATED);
            }

            //var_dump($stmt->errorInfo());
        } else
            $this->error = 'error_mising';
    }

    function del_data($id)
    {
        global $dbh;
        $tmp = '';
        $tripId = $this->getTripId($id);
        $query = "DELETE FROM " . $this->table . " WHERE id_timeline = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $tmp = $stmt->execute();
        if (!$tmp) {
            $this->error = 'error_fail';
        } else {
            ActivityLogger::log($tripId, ActivityLogger::EVENT_DELETED);
        }
    }

    private function getTripId($id)
    {
        global $dbh;
        $query = "select id_trip from {$this->table} where id_timeline = :id_timeline";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(":id_timeline", $id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
