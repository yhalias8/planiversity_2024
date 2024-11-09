<?php
    include_once("../config.ini.php");
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        
        if (!empty($first_name) && !empty($email)) {
            $query = "INSERT INTO affiliate (first_name, last_name, email, created_at) values (?, ?, ?, ?)";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $first_name, PDO::PARAM_STR);
            $stmt->bindValue(2, $last_name, PDO::PARAM_STR);
            $stmt->bindValue(3, $email, PDO::PARAM_STR);
            $stmt->bindValue(4, date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->execute();
            echo json_encode(['status' => true, 'message' => 'success']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Please fill required fields.']);
        }
    } else {
        header('Location:' . SITE);
    }
?>