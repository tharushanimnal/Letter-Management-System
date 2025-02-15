<?php
include 'database.php'; 
$query = "SELECT MAX(id) AS max_id FROM send";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $maxId = isset($row['max_id']) ? intval($row['max_id']) : 0;
    $nextId = $maxId + 1;
    echo json_encode(['next_id' => $nextId]);
} else {
    echo json_encode(['error' => 'Unable to fetch data']);
}
?>
