<?php
include 'database.php';

$selectedDate = $_POST['date'];

$query1 = "
    SELECT 
        s.name AS Department,
        COUNT(r.id) AS Total_Letters_Received,
        SUM(r.price) AS Total_Value_Of_Letters
    FROM 
        recieve r
    JOIN 
        send s ON r.id = s.ID
    WHERE 
        r.Rtime = ? AND r.checked = 1
    GROUP BY 
        s.name
    UNION ALL
    SELECT 
        'Grand Total' AS Department,
        COUNT(r.id) AS Total_Letters_Received,
        SUM(r.price) AS Total_Value_Of_Letters
    FROM 
        recieve r
    WHERE 
        r.Rtime = ? AND r.checked = 1
";

$stmt1 = $conn->prepare($query1);
$stmt1->bind_param('ss', $selectedDate, $selectedDate);
$stmt1->execute();
$result1 = $stmt1->get_result();

$query2 = "
    SELECT 
        r.price AS Price,
        COUNT(r.id) AS Count,
        SUM(r.price) AS Total
    FROM 
        recieve r
    WHERE 
        r.Rtime = ? AND r.checked = 1
    GROUP BY 
        r.price
    ORDER BY 
        r.price
";

$stmt2 = $conn->prepare($query2);
$stmt2->bind_param('s', $selectedDate);
$stmt2->execute();
$result2 = $stmt2->get_result();

$grandTotalCount = 0;
$grandTotalAmount = 0;

$output = '';

if ($result1->num_rows > 0) {
    $output .= "<h3 style='color:#ffffff;'>Summary for Date: $selectedDate</h3>";
    $output .= "<table border='1' cellpadding='10' cellspacing='0'>
                <tr>
                    <th>Department</th>
                    <th>Total Letters Received</th>
                    <th>Total Value</th>
                </tr>";
    while ($row = $result1->fetch_assoc()) {
        $output .= "<tr>
                        <td>{$row['Department']}</td>
                        <td>{$row['Total_Letters_Received']}</td>
                        <td>{$row['Total_Value_Of_Letters']}</td>
                    </tr>";
    }
    $output .= "</table>";
} else {
    $output .= "<p>No records found for the selected date.</p>";
}

if ($result2->num_rows > 0) {
    $output .= "<h3 style='color:#ffffff;'>Prices</h3>";
    $output .= "<table border='1' cellpadding='10' cellspacing='0'>
                <tr>
                    <th>Price</th>
                    <th>Count</th>
                    <th>Total</th>
                </tr>";
    while ($row = $result2->fetch_assoc()) {
        $grandTotalCount += $row['Count'];
        $grandTotalAmount += $row['Total'];

        $output .= "<tr>
                        <td>{$row['Price']}</td>
                        <td>{$row['Count']}</td>
                        <td>{$row['Total']}</td>
                    </tr>";
    }

    $output .= "<tr>
                    <td><strong>Grand Total</strong></td>
                    <td><strong>$grandTotalCount</strong></td>
                    <td><strong>$grandTotalAmount</strong></td>
                </tr>";
    $output .= "</table>";
} else {
    $output .= "<p>No records found for the price .</p>";
}

$stmt1->close();
$stmt2->close();
$conn->close();

echo $output;
?>
