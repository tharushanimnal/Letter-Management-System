<?php
require 'database.php'; 

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $fileId = $_GET['id']; 

    $stmt = $conn->prepare("SELECT pdf, name FROM letters WHERE id = ?");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($pdfContent, $fileName);

    if ($stmt->fetch()) {
        header('Content-Type: application/pdf');
        header('Content-Length: ' . strlen($pdfContent));
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        echo $pdfContent;
        exit;
    } else {
        echo "File not found!";
    }
} else {
    echo "No file specified.";
}
?>
