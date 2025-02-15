<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (is_array($input['records'])) {
        include 'database.php'; 

        foreach ($input['records'] as $record) {
            $id = $conn->real_escape_string($record['id']);
            $Sid = $conn->real_escape_string($record['Sid']);
            $name = $conn->real_escape_string($record['name']);
            $address = $conn->real_escape_string($record['address']);
            $price = $conn->real_escape_string($record['price']);
            $checked = isset($record['checked']) ? (int)$record['checked'] : 0;
            $time = isset($record['time']) && !empty($record['time']) ? $conn->real_escape_string($record['time']) : null;
            $Rtime = isset($record['Rtime']) && !empty($record['Rtime']) ? $conn->real_escape_string($record['Rtime']) : null;

            error_log('Rtime value received: ' . var_export($Rtime, true));

            $checkQuery = "SELECT id FROM recieve WHERE id = '$id'";
            $result = $conn->query($checkQuery);

            if ($result->num_rows > 0) {
                $updateQuery = "UPDATE recieve SET 
                                Sid='$Sid', 
                                name='$name', 
                                address='$address', 
                                price='$price', 
                                checked='$checked', 
                                time=" . ($time ? "'$time'" : "NULL") . " ,
                                Rtime=" . ($Rtime ? "'$Rtime'" : "NULL") . " 
                                WHERE id='$id'";
                if (!$conn->query($updateQuery)) {
                    throw new Exception("Error updating record: " . $conn->error);
                }
            } else {
                $insertQuery = "INSERT INTO recieve (id, Sid, name, address, price, checked, time, Rtime) 
                                VALUES ('$id', '$Sid', '$name', '$address', '$price', '$checked', " . ($time ? "'$time'" : "NULL") . ", " . ($Rtime ? "'$Rtime'" : "NULL") . ")";
                if (!$conn->query($insertQuery)) {
                    throw new Exception("Error inserting record: " . $conn->error);
                }
            }

            $updateSendQuery = "UPDATE send SET price = '$price' WHERE ID = '$id'";
            if (!$conn->query($updateSendQuery)) {
                throw new Exception("Error updating send table: " . $conn->error);
            }
        }

        $conn->close();
        echo json_encode(['success' => true]);
    } else {
        throw new Exception("Invalid input data");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
