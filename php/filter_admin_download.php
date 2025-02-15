<?php
include 'database.php'; 

$date = isset($_GET['date']) ? $_GET['date'] : '';

$query = "SELECT * FROM letters WHERE 1=1";
$params = [];
$types = "";

if (!empty($date)) {
    $query .= " AND DATE_FORMAT(date, '%Y-%m-%d') = ?";
    $types .= "s";
    $params[] = $date;
}

$stmt = $conn->prepare($query);

if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fileName = htmlspecialchars(basename($row['pdf']));
        echo "<tr>
                <td>" . htmlspecialchars($row['num']) . "</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['date']) . "</td>
                <td><a href='php/download.php?id=" . urlencode($row['ID']) . "' class='btn btn-primary btn-sm'>Download</a></td>
                <td><button type='button' class='btn btn-danger delete-button' data-type='download' data-id='" . htmlspecialchars($row['ID']) . "'>Delete</button></td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='3'>No results found</td></tr>";
}

$stmt->close();
$conn->close();
?>
