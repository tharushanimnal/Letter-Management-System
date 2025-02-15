<?php
include 'database.php';

$currentDate = date('Y-m-d');
$Date = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : $currentDate;
$Department = isset($_GET['department']) ? $_GET['department'] : '';

$query = "SELECT s.id, s.Sid, s.name, s.address, s.price, s.time, s.sub_date, r.Rtime 
          FROM send s
          LEFT JOIN recieve r ON s.Sid = r.Sid
          WHERE DATE_FORMAT(s.sub_date, '%Y-%m-%d') = ?";

$params = [$Date];
$types = "s";

if (!empty($Department)) {
    $query .= " AND s.name = ?";
    $types .= "s";
    $params[] = $Department;
}

$query .= " ORDER BY s.sub_date DESC";

$stmt = $conn->prepare($query);

if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='width:100px;'>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td style='width:100px;'>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td style='width:100px;'>" . htmlspecialchars($row['Sid']) . "</td>";
        echo "<td class='address-column' style='width:300px;'>" . htmlspecialchars($row['address']) . "</td>";
        echo "<td style='width:100px;'>" . htmlspecialchars($row['sub_date']) . "</td>";
        echo "<td style='width:100px;'>" . htmlspecialchars($row['Rtime']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No results found</td></tr>";
}

$stmt->close();
$conn->close();
?>
