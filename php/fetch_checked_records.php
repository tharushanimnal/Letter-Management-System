<?php
include 'database.php';

$currentDate = date('Y-m-d');

$sql = "SELECT Sid, ID, name, address, price, time FROM recieve WHERE checked = 1 AND DATE(time) = '$currentDate'";
$result = $conn->query($sql);
$query .= " ORDER BY time DESC";
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
$conn->close();
?>
