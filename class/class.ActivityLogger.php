<?php
/**
 * @author: Fabian Rolof <fabian@rolof.pl>
 */

include_once(__DIR__ . "/../config.ini.php");

class ActivityLogger {
    // Define constants for change types
    const PLAN_CREATED = 'PLAN_CREATED';
    const PLAN_DELETED = 'PLAN_DELETED';
    const DOCUMENT_ADDED = 'DOCUMENT_ADDED';
    const DOCUMENT_DELETED = 'DOCUMENT_DELETED';
    const USER_ADDED = 'USER_ADDED';
    const USER_DELETED = 'USER_DELETED';
    const EVENT_ADDED = 'EVENT_ADDED';
    const EVENT_UPDATED = 'EVENT_UPDATED';
    const EVENT_DELETED = 'EVENT_DELETED';
    const SUBPLAN_ADDED = 'SUBPLAN_ADDED';
    const SUBPLAN_UPDATED = 'SUBPLAN_UPDATED';
    const SUBPLAN_DELETED = 'SUBPLAN_DELETED';
    const NOTE_ADDED = 'NOTE_ADDED';
    const NOTE_UPDATED = 'NOTE_UPDATED';
    const NOTE_DELETED = 'NOTE_DELETED';
    const RESOURCES_ADDED = 'RESOURCES_ADDED';
    const RESOURCES_UPDATED = 'RESOURCES_UPDATED';
    const RESOURCES_DELETED = 'RESOURCES_DELETED';

    const PER_PAGE = 10;

    public static function log($tripId, $changeType) {
        global $dbh, $userdata;

        try {
            // Check if the changeType is valid
            if (!in_array($changeType, self::getAllowedChangeTypes())) {
                throw new InvalidArgumentException("Invalid change type: $changeType");
            }

            $sql = "INSERT INTO activities (user_id, trip_id, change_type) VALUES (:user_id, :trip_id, :change_type)";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                ':user_id' => $userdata['id'],
                ':trip_id' => $tripId,
                ':change_type' => $changeType
            ]);
        } catch (Exception $e) {
            // Log or handle the error as needed
            error_log('Error logging activity: ' . $e->getMessage());
        }
    }

    // Return all allowed change types
    private static function getAllowedChangeTypes() {
        return [
            self::PLAN_CREATED,
            self::PLAN_DELETED,
            self::DOCUMENT_ADDED,
            self::DOCUMENT_DELETED,
            self::USER_ADDED,
            self::USER_DELETED,
            self::EVENT_ADDED,
            self::EVENT_UPDATED,
            self::EVENT_DELETED,
            self::SUBPLAN_ADDED,
            self::SUBPLAN_UPDATED,
            self::SUBPLAN_DELETED,
            self::NOTE_ADDED,
            self::NOTE_UPDATED,
            self::NOTE_DELETED,
            self::RESOURCES_ADDED,
            self::RESOURCES_UPDATED,
            self::RESOURCES_DELETED
        ];
    }

    public static function getUserActivities($userId, $page = 1) {
        global $dbh;

        $offset = ($page - 1) * self::PER_PAGE;
        $sql = "SELECT DISTINCT a.id, a.timestamp, a.change_type, COALESCE(NULLIF(t.title, ''), '**NO NAME**') AS plan_title,  
                       author.picture as author_picture, author.name AS author_name, author.email AS author_email, author.customer_number AS author_customer_number,
                       receiver.name AS receiver_name, receiver.email AS receiver_email, receiver.customer_number AS receiver_customer_number,
                       a.trip_id
                FROM activities a
                INNER JOIN trips t ON t.id_trip = a.trip_id
                LEFT JOIN connect_master cm ON a.trip_id = cm.id_trip
                LEFT JOIN connect_details cd ON cd.connect_id = cm.id
                INNER JOIN users author ON author.id = a.user_id
                LEFT JOIN employees e ON (e.id_employee = cd.people_id AND t.id_user != :user_id)
                LEFT JOIN users receiver ON (receiver.customer_number = e.employee_id OR t.id_user = receiver.id)
                WHERE :user_id IN (receiver.id, t.id_user)
                ORDER BY a.timestamp DESC
                LIMIT " . self::PER_PAGE . " OFFSET :offset";

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countSql = "SELECT COUNT(DISTINCT a.id) 
                     FROM activities a
                     INNER JOIN trips t ON t.id_trip = a.trip_id
                     LEFT JOIN connect_master cm ON a.trip_id = cm.id_trip
                     LEFT JOIN connect_details cd ON cd.connect_id = cm.id
                     INNER JOIN users author ON author.id = a.user_id
                     LEFT JOIN employees e ON (e.id_employee = cd.people_id AND t.id_user != :user_id)
                     LEFT JOIN users receiver ON (receiver.customer_number = e.employee_id OR t.id_user = receiver.id)
                     WHERE (:user_id IN (receiver.id, t.id_user))";

        $countStmt = $dbh->prepare($countSql);
        $countStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $countStmt->execute();

        $totalActivities = $countStmt->fetchColumn();

        return ['activities' => $activities, 'total' => $totalActivities];
    }

    public static function getNumberOfUnreadNotifications($userId)
    {
        global $dbh;
        $countSql = "SELECT COUNT(DISTINCT a.id) 
                     FROM activities a
                     INNER JOIN trips t ON t.id_trip = a.trip_id
                     LEFT JOIN connect_master cm ON a.trip_id = cm.id_trip
                     LEFT JOIN connect_details cd ON cd.connect_id = cm.id
                     INNER JOIN users author ON author.id = a.user_id
                     LEFT JOIN employees e ON (e.id_employee = cd.people_id AND t.id_user != :user_id)
                     LEFT JOIN users receiver ON (receiver.customer_number = e.employee_id OR t.id_user = receiver.id)
                     WHERE (:user_id IN (receiver.id, t.id_user)) and receiver.notification_checked_at <= a.`timestamp`";

        $countStmt = $dbh->prepare($countSql);
        $countStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $countStmt->execute();

        $ret = $countStmt->fetchColumn();
        return ($ret>0?$ret:'');
    }

    public static function markVisitOnActivityPage($userId)
    {
        global $dbh;
        $sql = "update users set notification_checked_at=now() where id=:user_id";
        $smt = $dbh->prepare($sql);
        $smt->bindParam(":user_id", $userId, PDO::PARAM_INT);
        $smt->execute();
    }

    public static function getHumanReadableMessage($changeType, $tripId, $authorName, $title) {
        $linkTpl = '<a href="%s" style="color:#000;font-weight: bold">%s</a>';
        $messages = [
            self::PLAN_CREATED => '<b>%s</b> created a plan for you: %s.',
            self::PLAN_DELETED => '<b>%s</b> deleted your plan.',
            self::DOCUMENT_ADDED => '<b>%s</b> added a new document to your %s.',
            self::DOCUMENT_DELETED => '<b>%s</b> deleted a document in your %s.',
            self::USER_ADDED => '<b>%s</b> added a new user to your %s.',
            self::USER_DELETED => '<b>%s</b> deleted a user in your %s.',
            self::EVENT_ADDED => '<b>%s</b> added a new event to your %s.',
            self::EVENT_UPDATED => '<b>%s</b> updated an event in your %s.',
            self::EVENT_DELETED => '<b>%s</b> deleted an event in your %s.',
            self::SUBPLAN_ADDED => '<b>%s</b> added a new scheduled time to your %s.',
            self::SUBPLAN_UPDATED => '<b>%s</b> updated a scheduled time in your %s.',
            self::SUBPLAN_DELETED => '<b>%s</b> deleted a scheduled time in your %s.',
            self::NOTE_ADDED => '<b>%s</b> added a new note to your %s.',
            self::NOTE_UPDATED => '<b>%s</b> updated a note in your %s.',
            self::NOTE_DELETED => '<b>%s</b> deleted a note in your %s.',
            self::RESOURCES_ADDED => '<b>%s</b> added a new resource to your %s.',
            self::RESOURCES_UPDATED => '<b>%s</b> updated a resource in your %s.',
            self::RESOURCES_DELETED => '<b>%s</b> deleted a resource in your %s.'
        ];

        $links = [
            self::PLAN_CREATED => "trip/connect/$tripId",
            self::PLAN_DELETED => "",
            self::DOCUMENT_ADDED => "trip/travel-documents/$tripId",
            self::DOCUMENT_DELETED => "trip/travel-documents/$tripId",
            self::USER_ADDED => "trip/connect/$tripId",
            self::USER_DELETED => "trip/connect/$tripId",
            self::EVENT_ADDED => "trip/create-timeline/$tripId",
            self::EVENT_UPDATED => "trip/create-timeline/$tripId",
            self::EVENT_DELETED => "trip/create-timeline/$tripId",
            self::SUBPLAN_ADDED => "trip/plans/$tripId",
            self::SUBPLAN_UPDATED => "trip/plans/$tripId",
            self::SUBPLAN_DELETED => "trip/plans/$tripId",
            self::NOTE_ADDED => "trip/plan-notes/$tripId",
            self::NOTE_UPDATED => "trip/plan-notes/$tripId",
            self::NOTE_DELETED => "trip/plan-notes/$tripId",
            self::RESOURCES_ADDED => "trip/resources/$tripId",
            self::RESOURCES_UPDATED => "trip/resources/$tripId",
            self::RESOURCES_DELETED => "trip/resources/$tripId"
        ];

        $message = $messages[$changeType] ?? 'Unknown change type';
        $link = $links[$changeType];

        if ($link) {
            return sprintf($message, $authorName, sprintf($linkTpl, $link, $title));
        } else {
            return sprintf($message, $authorName);
        }
    }

    public static function getUserActivityPage($userId, $page = 1) {
        $data = self::getUserActivities($userId, $page);
        $activities = $data['activities'];
        $totalActivities = $data['total'];
        $result = [];

        foreach ($activities as $activity) {
            $result[] = [
                'author_picture' => $activity['author_picture'],
                'author_name' => $activity['author_name'],
                'timestamp' => $activity['timestamp'],
                'message' => self::getHumanReadableMessage(
                    $activity['change_type'],
                    $activity['trip_id'],
                    $activity['author_name'],
                    $activity['plan_title']
                )
            ];
        }

        return [
            'activities' => $result,
            'total_activities' => $totalActivities,
            'page' => $page
        ];
    }
}
