<?php
include 'database.php';

$updates = json_decode(file_get_contents('php://input'), true);

if ($updates) {
    foreach ($updates as $update) {
        $stmt = $conn->prepare("UPDATE recieve SET checked = ? WHERE ID = ?");
        $stmt->bind_param("ii", $update['checked'], $update['id']);

        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
            exit;
        }
    }
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'No data received']);
}

$stmt->close();
$conn->close();
?>
