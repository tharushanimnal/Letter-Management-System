<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $num = $_POST['num'];

    $checkStmt = $conn->prepare("SELECT COUNT(*) AS count FROM letters WHERE num = ?");
    $checkStmt->bind_param("s", $num);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        header("Location: ../admin.html?error=1");
        exit;
    }

    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
        $pdfContent = file_get_contents($_FILES['pdf']['tmp_name']);
        $fileName = basename($_FILES['pdf']['name']); 

        $stmt = $conn->prepare("INSERT INTO letters (num, pdf, name, date) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $num, $pdfContent, $fileName);

        if ($stmt->execute()) {
            header("Location: ../admin.html?success=1");
            exit;
        } else {
            echo "Failed to save data!";
        }
    } else {
        echo "Please upload a valid PDF file.";
    }
}
?>
