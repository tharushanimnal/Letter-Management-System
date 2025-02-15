<?php
include 'database.php';

$currentDate = date('Y-m-d');
$Date = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : $currentDate;
$Department = isset($_GET['department']) ? $_GET['department'] : '';

$query = "SELECT id, Sid, name, address, price, time, Rtime FROM recieve WHERE checked=1";
$params = [];
$types = "";

if (!empty($Date)) {
    $query .= " AND DATE_FORMAT(Rtime, '%Y-%m-%d') = ?";
    $types .= "s";
    $params[] = $Date;
}

if (!empty($Department)) {
    $query .= " AND name = ?";
    $types .= "s";
    $params[] = $Department;
}

$query .= " ORDER BY Rtime DESC";

$stmt = $conn->prepare($query);

if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$totalPrice = 0;

if ($result->num_rows > 0) {
    $counter = 1; 
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='width:100px;'>" . $counter++ . "</td>"; 
        echo "<td style='width:100px;'>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td style='width:100px;'>" . htmlspecialchars($row['Sid']) . "</td>";
        echo "<td class='address-column' style='width:300px;'>" . htmlspecialchars($row['address']) . "</td>";
        echo "<td style='width:100px;'> Rs." . htmlspecialchars($row['price']) . "</td>";
        echo "</tr>";

        $totalPrice += (float)$row['price'];
    }

    echo "<tr style='font-weight:bold; background-color: #f8f9fa;'>";
    echo "<td colspan='4' style='text-align:right;'>Grand Total:</td>";
    echo "<td> Rs." . number_format($totalPrice, 2) . "</td>";
    echo "</tr>";
} else {
    echo "<tr><td colspan='5'>No results found</td></tr>";
}

$stmt->close();
$conn->close();
?>
