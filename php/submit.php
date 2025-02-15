<?php
include 'database.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Sid = $_POST['id'];
    $id = $_POST['num'];
    $name = $_POST['option1'];
    $address = $_POST['address'];
    $price = $_POST['price'];
    $Date = $_POST['date'];

    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM send WHERE ID = ?");
    if ($checkStmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $checkStmt->bind_param("s", $id);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        header("Location: /Letter-Management/index.html?error=1");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO send (ID, Sid, name, address, price, time) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $Sid, $id, $name, $address, $price, $Date);

    if ($stmt->execute()) {
        header("Location: /Letter-Management/index.html?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
