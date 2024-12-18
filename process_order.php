<?php 
require_once 'db_connector.php';
include 'header.php';

$subtotal = 0;
$tax_rate = 0.0625;  // 6.25% tax

// Collect necessary details to store in the orders table
$first_name = $_GET['first_name'];
$last_name = $_GET['last_name'];
$pickup_time = $_GET['pickup_time'];
$special_instructions = $_GET['special_instructions'];
$order_items = []; // Array to collect items that will be inserted

?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .details {
            margin-top: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            background-color: white;
            transition: all 0.3s ease;
        }

        .details:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        }

        .details p {
            margin-left: 100px;
            line-height: 1.6;
            color: #333;
            transition: color 0.2s ease;
        }

        .details:hover p {
            color: #000;
        }
    </style>
</head>
<body>
    <h2>Order Details</h2>
    
    <table>
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        
        <?php
        
        // Loop through each item and retrieve data from the database
        foreach ($_GET['quantity'] as $item_id => $quantity) {
            if ($quantity > 0) {
                // Prevent SQL injection by using prepared statements
                $query = "SELECT name, price FROM menu WHERE id = ?";
                $stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($stmt, "i", $item_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $item = mysqli_fetch_assoc($result);
                
                if ($item) {
                    // Calculate the total price for the item
                    $item_total = $item['price'] * $quantity;
                    $subtotal += $item_total;

                    // Store item details for later insertion
                    $order_items[] = [
                        'name' => $item['name'],
                        'quantity' => $quantity,
                        'total' => $item_total
                    ];

                    // Display item in the table
                    echo "<tr>";
                    echo "<td>{$item['name']}</td>";
                    echo "<td>$quantity</td>";
                    echo "<td>\${$item['price']}</td>";
                    echo "<td>\$" . number_format($item_total, 2) . "</td>";
                    echo "</tr>";
                }
                mysqli_stmt_close($stmt);
            }
        }
        ?>
    </table>

    <div class="details">
        <p>Subtotal: $<?= number_format($subtotal, 2) ?></p>
        <p>Tax (6.25%): $<?= number_format($subtotal * $tax_rate, 2) ?></p>
        <p>Total: $<?= number_format($subtotal * (1 + $tax_rate), 2) ?></p>
        <p>Pickup Time: <?= $_GET['pickup_time'] ?></p>
        <p>Name: <?= $_GET['first_name'] . ' ' . $_GET['last_name'] ?></p>
        <p>Special Instructions: <?= $_GET['special_instructions'] ?></p>

    </div>
</body>
</html>

<?php
// Calculate total with tax
$total_price = $subtotal * (1 + $tax_rate);
$ordered_date = date("Y-m-d H:i:s");

// Build the items string for the orders table
$items_list = '';
foreach ($order_items as $item) {
    $items_list .= "{$item['name']} ({$item['quantity']}) - \${$item['total']}, ";
}
$items_list = rtrim($items_list, ', ');

// Insert into orders table
$order_query = "INSERT INTO orders (ordered_date, name, pickup_time, items, special_instructions, total) 
                VALUES (?, ?, ?, ?, ?, ?)";
$order_stmt = mysqli_prepare($connection, $order_query);

// Combine first and last name
$full_name = $first_name . ' ' . $last_name;

mysqli_stmt_bind_param($order_stmt, "sssssd",
    $ordered_date,          
    $full_name,
    $pickup_time, 
    $items_list,
    $special_instructions,
    $total_price
);

if (mysqli_stmt_execute($order_stmt)) {
    echo "<p>Order successfully saved!</p>";
} else {
    echo "<p>Error saving the order: " . mysqli_error($connection) . "</p>";
}

mysqli_close($connection);
?>