<?php

include '../../config.ini.php';

if (!$auth->isLogged()) {
    header('location: ../../');
}

if (isset($_GET['id_trip'])) {

    $id_trip = $_GET['id_trip'];

    $sql = <<<SQL
select * from (
(
	SELECT 'checked_in' as type, timeline.date, timeline.title,timeline.checked_in_date,users.name,users.picture 
    FROM timeline 
    LEFT JOIN users ON timeline.checked_in_user = users.id 
    WHERE id_trip=:trip_id
		and is_checked=:is_checked
        and checked_in=:checked_in
) union 
select * from ((
	select 'status_update' as type, us.ts as date, us.`status` as title,us.ts as checked_in ,u.name, u.picture 
    from update_status us 
    inner join users u on u.id=us.user_id 
    where us.trip_id=:trip_id 
		and us.`for`='all' 
        )
	union ( 
		select 'status_update' as type, us.ts as date, us.`status`,us.ts as checked_idn,u.name, u.picture 
        from update_status us 
        inner join users u on u.id=us.user_id 
        inner join update_status_users usu on usu.update_status_id=us.id 
        where us.`for`='selected' 
			and us.trip_id=:trip_id 
            and usu.user_id=:user_id
)) as xx
) as x order by date desc 
SQL;


    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":trip_id", $id_trip, PDO::PARAM_INT);
    $stmt->bindValue(":is_checked", 1, PDO::PARAM_INT);
    $stmt->bindValue(":checked_in", 1, PDO::PARAM_INT);
    $stmt->bindParam(":user_id", $userdata['id'], PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $aux = '';
    $timelines = [];

    if ($tmp && $stmt->rowCount() > 0) {
        $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    $data = array();

    foreach ($timelines as $timeline) {
        $checked_in_date = new DateTime($timeline->checked_in_date);
        $dateFormatted = $checked_in_date->format('h:i a \o\n F jS, Y');

        $data[] = array(
            'type' => $timeline->type,
            "checked_in_date" => $dateFormatted,
            "event_name" => $timeline->title,
            "name" => $timeline->name,
            "picture" => $timeline->picture,
        );
    }



    $jsonObject = json_encode($data);

    echo ($jsonObject);
}
