<?php
include 'database.php';

$Date = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : null;
$Department = isset($_GET['department']) ? $_GET['department'] : '';
$subId = isset($_GET['subId']) ? $_GET['subId'] : '';
$letId = isset($_GET['letId']) ? $_GET['letId'] : '';

$query = "SELECT Sid, name, ID, address, price, time FROM send WHERE 1=1";

$params = [];
$types = "";

if (!empty($subId)) {
    $query .= " AND Sid = ?";
    $types .= "s";
    $params[] = $subId;
}

if (!empty($letId)) {
    $query .= " AND ID = ?";
    $types .= "s";
    $params[] = $letId;
}

if (!empty($Date)) {
    $query .= " AND DATE_FORMAT(time, '%Y-%m-%d') = ?";
    $types .= "s";
    $params[] = $Date;
}

if (!empty($Department)) {
    $query .= " AND name = ?";
    $types .= "s";
    $params[] = $Department;
}

$query .= " ORDER BY time DESC";

$stmt = $conn->prepare($query);

if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='width:100px;'>" . htmlspecialchars($row['ID']) . "</td>";
        echo "<td style='width:150px;'>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td style='width:100px;'>" . htmlspecialchars($row['Sid']) . "</td>";
        echo "<td class='address-column'>" . htmlspecialchars($row['address']) . "</td>";
        echo "<td style='width:100px;'>" . htmlspecialchars($row['price']) . "</td>";
        echo "<td style='width:200px;'>" . htmlspecialchars($row['time']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No results found</td></tr>";
}

$stmt->close();
$conn->close();

?>
