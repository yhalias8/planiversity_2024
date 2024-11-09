<?php

class Note
{
    var $note_id = 0;
    var $trip_id = 0;
    var $note_text = '';
    var $note_date = '';
    var $table = '';
    var $error = '';

    function Note($nametable = 'notes')
    {
        $this->note_id = 0;
        $this->trip_id = 0;
        $this->note_text = '';
        $this->note_date = '';
        $this->table = $nametable;
        $this->error = '';
    }

    function put_data($trip_id, $text)
    {
        global $dbh;
        $stmt = $tmp = $query = '';
        $this->error = '';
        if (!empty($trip_id) && !empty($text)) {
            $query = "INSERT INTO " . $this->table . " (id_trip, text, date) VALUES (?, ?, ?)";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $trip_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $text, PDO::PARAM_STR);
            $stmt->bindValue(3, date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $tmp = $stmt->execute();
            if (!$tmp) {
                $this->error = 'error_fail';
            } else {
                ActivityLogger::log($trip_id, ActivityLogger::NOTE_ADDED);
            }
        } else {
            $this->error = 'error_mising';
        }
    }

    function edit_data($note_id, $text)
    {
        global $dbh;
        $stmt = $tmp = $query = '';
        $this->error = '';
        if (!empty($note_id) && !empty($text)) {
            $query = "UPDATE " . $this->table . " SET text = ? WHERE id_note = ?";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $text, PDO::PARAM_STR);
            $stmt->bindValue(2, $note_id, PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if (!$tmp) {
                $this->error = 'error_fail';
            } else {
                ActivityLogger::log($this->getTripId($note_id), ActivityLogger::NOTE_UPDATED);
            }
        } else {
            $this->error = 'error_mising';
        }
    }

    function del_data($id)
    {
        global $dbh;
        $tmp = '';
        $tripId = $this->getTripId($id);
        $query = "DELETE FROM " . $this->table . " WHERE id_note = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $tmp = $stmt->execute();
        if (!$tmp) {
            $this->error = 'error_fail';
        } else {
            ActivityLogger::log($tripId, ActivityLogger::NOTE_DELETED);
        }
    }

    private function getTripId($noteId)
    {
        global $dbh;
        $query = "select id_trip from {$this->table} where id_note = :id_note";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(":id_note", $noteId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

}
