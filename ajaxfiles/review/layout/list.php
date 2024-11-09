<?php
//include '../../../config.ini.php';


function notes($dbh, $user_id, $id_trip)
{
    $html = '';
    $stmt = $dbh->prepare("SELECT * FROM notes WHERE id_trip=? ORDER BY date");
    $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
    $tmp = $stmt->execute();

    if ($tmp && $stmt->rowCount() > 0) {
        $notes = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($notes as $note) {

            $edit_props = "edit_note($note->id_note)";
            $delete_props = "del_element($note->id_note,'notes')";
            $html .= "<div class='review-wrapper' id='notes_$note->id_note'>

                                            <div class='review-content'>
                                                <i class='fa fa-sticky-note-o'></i>
                                                <p>" . $note->text . "</p>
                                            </div>

                                            <div class='review-action'>
                                                <button type='button' class='btn review-btn review-edit' onclick=$edit_props>
                                                    <i class='fa fa-pencil'></i>
                                                </button>

                                                <button type='button' class='btn review-btn review-delete' onclick=$delete_props>
                                                    <i class='fa fa-trash'></i>
                                                </button>

                                            </div>

                                        </div>";
        }
    }

    return $html;
}



function timeline($dbh, $user_id, $id_trip)
{
    $html = '';
    $stmt = $dbh->prepare("SELECT * FROM timeline WHERE id_trip=? ORDER BY date");
    $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
    $tmp = $stmt->execute();

    if ($tmp && $stmt->rowCount() > 0) {
        $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($timelines as $item) {

            $edit_props = "edit_timelines($item->id_timeline)";
            $delete_props = "del_element($item->id_timeline,'timelines')";
            $date_formate = date('d F Y h:i a', strtotime($item->date));
            $html .= "<div class='review-wrapper' id='timelines_$item->id_timeline'>

                                            <div class='review-content'>
                                                <i class='fa fa-calendar-check-o'></i>
                                                <p>$item->title  ( $date_formate ) </p>
                                            </div>

                                            <div class='review-action'>
                                                <button type='button' class='btn review-btn review-edit' onclick=$edit_props>
                                                    <i class='fa fa-pencil'></i>
                                                </button>

                                                <button type='button' class='btn review-btn review-delete' onclick=$delete_props>
                                                    <i class='fa fa-trash'></i>
                                                </button>

                                            </div>

                                        </div>";
        }
    }

    return $html;
}


function documents($dbh, $user_id, $id_trip)
{
    $html = '';
    $stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND td.id_trip=?");
    $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
    $tmp = $stmt->execute();

    if ($tmp && $stmt->rowCount() > 0) {
        $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($documents as $item) {

            $edit_props = "edit_timelines($item->id_document)";
            $delete_props = "del_element($item->id_document,'documents')";
            $date_formate = date('d F Y h:i a', strtotime($item->date));
            $html .= "<div class='review-wrapper' id='documents_$item->id_document'>

                                            <div class='review-content'>
                                                <i class='fa fa-file-text-o'></i>
                                                <p>$item->name</p>
                                            </div>

                                            <div class='review-action' align='right'>
                                                <button type='button' class='btn review-btn review-delete' onclick=$delete_props>
                                                    <i class='fa fa-trash'></i>
                                                </button>

                                            </div>

                                        </div>";
        }
    }

    return $html;
}
