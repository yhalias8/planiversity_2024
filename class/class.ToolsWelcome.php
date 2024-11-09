<?php
/**
 * @author: Fabian Rolof <fabian@rolof.pl>
 */


class ToolsWelcome
{
    public static function generateTripEvents($dbh, $userdata, $itineraryType, $color, $school) {
        $stmt = $dbh->prepare("
        (SELECT * FROM trips WHERE pdf_generated=1 AND itinerary_type=? AND (DATE(NOW()) <= DATE(location_datel) OR DATE(NOW()) <= DATE(location_datel_arr)) AND id_user=?)
        UNION ALL
        (SELECT * FROM trips WHERE pdf_generated=1 AND itinerary_type=? AND (DATE(NOW()) <= DATE(location_datel) OR DATE(NOW()) <= DATE(location_datel_arr)) AND id_trip IN (SELECT trip_id FROM migration_master WHERE modifier_user_id=? AND status NOT IN ('pending', 'declined')))
        ORDER BY location_datel ASC
    ");
        $stmt->bindValue(1, $itineraryType, PDO::PARAM_STR);
        $stmt->bindValue(2, $userdata['id'], PDO::PARAM_INT);
        $stmt->bindValue(3, $itineraryType, PDO::PARAM_STR);
        $stmt->bindValue(4, $userdata['id'], PDO::PARAM_INT);
        $tmp = $stmt->execute();

        $aux = '';
        if ($tmp && $stmt->rowCount() > 0) {
            $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($timelines as $timeline) {
                $employee_ = '';
                if (!empty($timeline->id_employee)) {
                    $employee_ = '\n' . get_employee($timeline->id_employee);
                }

                $triptitle = trim($timeline->title);
                $triptitle = str_replace('&#39;', '_', $triptitle);
                $triptitle = str_replace(' ', '_', $triptitle);
                $pdfname = $triptitle . '-' . $timeline->id_trip;

                if ($timeline->location_datel && $timeline->location_datel != '0000-00-00') {
                    $date_start = $timeline->location_datel;
                } else {
                    $date_start = $timeline->date_created;
                }

                if ($timeline->location_dater && $timeline->location_dater != '0000-00-00') {
                    $date_end = $timeline->location_dater . ' 20:25:17';
                } else {
                    $date_end = $timeline->date_created;
                }

                $aux .= "{ 
                title: '" . $triptitle . "', 
                start: '" . date('Y-m-d', strtotime($date_start)) . "',
                end: '" . date('Y-m-d', strtotime($date_end) + 3600) . "T23:59:00',
                url: '" . SITE . "pdf/" . $pdfname . ".pdf',
                school: " . $school . ",
                color: '" . $color . "'
            },";
            }
        }
        return $aux;
    }



}