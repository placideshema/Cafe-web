<?php
include 'header.php'; 
// Database connection settings for My SiteGround
$servername = "127.0.0.1"; 
$username = "uahqdqmnp2gba";
$password = "FabriceMukarage23@";
$dbname = "dblbjpkntadz0b";

// Create connection
$connection = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to get all orders, ordered by most recent first
$query = "SELECT * FROM orders ORDER BY id";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection)); // Debugging query error
}
mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Orders</title>
    <style>
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .instructions {
            max-width: 300px;
            word-wrap: break-word;
        }
        .items {
            max-width: 400px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <h2>All Orders</h2>
    
    <table>
        <tr>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Customer Name</th>
            <th>Pickup Time</th>
            <th>Items</th>
            <th>Total</th>
            <th>Special Instructions</th>
        </tr>
        
        <?php
        // Check if there are rows
        if (mysqli_num_rows($result) > 0) {
            while ($orders = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$orders['id']}</td>";
                echo "<td>{$orders['ordered_date']}</td>";
                echo "<td>{$orders['name']}</td>";
                echo "<td>{$orders['pickup_time']}</td>";
                echo "<td class='items'>{$orders['items']}</td>";
                echo "<td>$" . number_format($orders['total'], 2) . "</td>";
                echo "<td class='instructions'>" . (!empty($orders['special_instructions']) ? $orders['special_instructions'] : 'None') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align: center;'>No orders have been placed yet.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
