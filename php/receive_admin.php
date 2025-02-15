<?php
include 'database.php';

$currentDate = date('Y-m-d');
$Date = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : $currentDate;
$Department = isset($_GET['department']) ? $_GET['department'] : '';

$query = "SELECT s.Sid, s.ID, s.name, s.address, s.price, s.time, r.checked, r.Rtime 
          FROM send s 
          LEFT JOIN recieve r ON s.ID = r.ID 
          WHERE DATE_FORMAT(s.time, '%Y-%m-%d') = ?";

$params = [$Date];
$types = "s";

if (!empty($Department)) {
    $query .= " AND s.name = ?";
    $types .= "s";
    $params[] = $Department;
}

$query .= " ORDER BY s.time DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='width:175px;'>" . htmlspecialchars($row['ID']) . "</td>";
        echo "<td style='width:100px;'>" . htmlspecialchars($row['Sid']) . "</td>";
        echo "<td style='width:175px;'>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td class='address-column'>" . htmlspecialchars($row['address']) . "</td>";
        echo "<td><input type='text' id='price" . htmlspecialchars($row['ID']) . "' name='price' value='" . htmlspecialchars($row['price']) . "' class='form-control' style='width:100px;' /></td>";
        echo "<td style='width:150px;'>
        <input type='date' id='time" . htmlspecialchars($row['ID']) . "' name='time_display' value='" . htmlspecialchars($row['time']) . "' class='form-control' style='background-color: white; border: none;' disabled />
        <input type='hidden' name='time' value='" . htmlspecialchars($row['time']) . "' /> </td>";

        
        echo "<td style='width:150px;'><input type='date' id='Rtime" . htmlspecialchars($row['ID']) . "' name='Rtime' value='" . htmlspecialchars($row['Rtime']?: $currentDate) . "' class='form-control' style='width:150px;' /></td>";
        
        $isChecked = $row['checked'] == 1 ? "checked" : "";
        echo "<td><input type='checkbox' class='received-checkbox ms-4' " . $isChecked . "></td>";
        echo "<td><button type='button' class='btn btn-danger delete-button' data-type='record' data-id='" . htmlspecialchars($row['ID']) . "'>Delete</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='9'>No records found</td></tr>";
}

$stmt->close();
$conn->close();
?>
