<?php
include 'database.php';

$query = "SELECT s.ID,s.Sid, s.name, s.address, s.price, s.time, r.Rtime 
          FROM send s 
          LEFT JOIN recieve r ON s.ID = r.ID 
          WHERE r.checked = 0 OR r.checked IS NULL 
          ORDER BY s.time DESC";


$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='width:175px;'>" . htmlspecialchars($row['ID']) . "</td>";
        echo "<td style='width:100px;'>" . htmlspecialchars($row['Sid']) . "</td>";
        echo "<td style='width:175px;'>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td class='address-column'>" . htmlspecialchars($row['address']) . "</td>";
        echo "<td style='width:100px;'><input type='text' id='price" . htmlspecialchars($row['ID']) . "' name='price' value='" . htmlspecialchars($row['price']) . "' class='form-control' /></td>";
        echo "<td style='width:150px;'>
        <input type='date' id='time" . htmlspecialchars($row['ID']) . "' name='time_display' value='" . htmlspecialchars($row['time']) . "' class='form-control' style='background-color: white; border: none;' disabled />
        <input type='hidden' name='time' value='" . htmlspecialchars($row['time']) . "' /> </td>";    
        echo "<td style='width:150px;'><input type='date' id='Rtime" . htmlspecialchars($row['ID']) . "' name='Rtime' value='" . htmlspecialchars($row['Rtime']) . "' class='form-control' style='width:150px;' /></td>";
        echo "<td><input type='checkbox' class='received-checkbox'></td>";
        echo "<td><button type='button' class='btn btn-danger delete-button'  data-type='record' data-id='" . htmlspecialchars($row['ID']) . "'>Delete</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No pending records found</td></tr>";
}

$stmt->close();
$conn->close();
?>
