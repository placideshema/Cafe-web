<?php 
require_once 'db_connector.php';

//header
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two Owls Cafe - Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        .menu-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(390px, 1fr));
            gap: 20px;
        }
        
        .menu-item { 
            display: flex; 
            align-items: center;
            margin-bottom: 15px; 
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            background-color: white;
            transition: all 0.3s ease;

        }

        .menu-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        }
        .menu-item img { 
            width: 200px;
            height: 200px;
            object-fit: cover;
            margin-right: 15px; 
            border-radius: 8px;
        }
        .menu-item-details {
            flex-grow: 1;
        }
        .menu-item select {
            margin-top: 10px;
            padding: 5px;
        }
        .form-section {
            margin-top: 20px;
            display: grid;
            gap: 15px;
        }
        .form-section div {
            display: flex;
            flex-direction: column;
        }
        input, textarea, select {
            margin-top: 5px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .total-price {
            margin-top: 20px;
            font-weight: bold;
            text-align: right;
        }
        .submit-btn{
            color: blue; 
        }
    </style>
</head>
<body>
    <h1>Two Owls Cafe - Order</h1>
    <form action="process_order.php" method="get" id="orderForm" novalidate>
        <div class="menu-container">
            <?php
            // Secure query preparation
            $query = "SELECT * FROM menu ORDER BY name";
            $stmt = mysqli_prepare($connection, $query);
            
            if ($stmt) {
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    while ($item = mysqli_fetch_assoc($result)) {
                        // Sanitize output
                        $name = htmlspecialchars($item['name']);
                        $description = htmlspecialchars($item['description']);
                        $image = htmlspecialchars($item['image']);
                        $price = number_format($item['price'], 2);
                        
                        echo "<div class='menu-item'>";
                        
                        // Image with error handling
                        $imagePath = "images/" . $image;
                        echo "<img src='" . $imagePath . "' alt='{$name}' 
                              onerror='this.onerror=null; this.src=\"images/placeholder.jpg\";'>";
                        
                        echo "<div class='menu-item-details'>";
                        echo "<h3>{$name}</h3>";
                        echo "<p>{$description}</p>";
                        echo "<p>Price: \${$price}</p>";
                        echo "<select name='quantity[{$item['id']}]' 
                                    data-price='{$item['price']}' 
                                    class='item-quantity' 
                                    aria-label='Quantity for {$name}'>";
                        echo "<option value='0'>0</option>";
                        for ($i = 1; $i <= 10; $i++) {
                            echo "<option value='$i'>$i</option>";
                        }
                        echo "</select>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No menu items available at the moment.</p>";
                }
                
                // Close statement
                mysqli_stmt_close($stmt);
            } else {
                echo "<p>Error loading menu items.</p>";
            }
            ?>
        </div>

        <div class="total-price">
            Total Price: $<span id="totalPrice">0.00</span>
        </div>

        <div class="form-section">
            <div>
                <label for="firstName">First Name:</label>
                <input type="text" name="first_name" id="firstName" required 
                       pattern="[A-Za-z\s'-]+" 
                       minlength="2" 
                       maxlength="50"
                       aria-required="true">
            </div>

            <div>
                <label for="lastName">Last Name:</label>
                <input type="text" name="last_name" id="lastName" required 
                       pattern="[A-Za-z\s'-]+" 
                       minlength="2" 
                       maxlength="50"
                       aria-required="true">
            </div>

            <div>
                <label for="specialInstructions">Special Instructions:</label>
                <textarea name="special_instructions" id="specialInstructions" 
                          maxlength="500" 
                          placeholder="Any additional requests (optional)"></textarea>
            </div>
        </div>

        <input type="hidden" name="pickup_time" id="pickupTime">
        <input type="hidden" name="total_price" id="hiddenTotalPrice">
        <div style="margin-top: 20px;">
            <button type="submit" class="submit-btn">Submit Order</button>
        </div>
    </form>

    <script>
        // Calculate pickup time
        function calculatePickupTime() {
            const now = new Date();
            now.setMinutes(now.getMinutes() + 20);
            
            const formattedTime = now.toLocaleTimeString('en-US', {
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true
            });
            
            document.getElementById('pickupTime').value = formattedTime;
        }

        // Calculate total price
        function calculateTotalPrice() {
            let total = 0;
            const quantitySelects = document.querySelectorAll('.item-quantity');
            
            quantitySelects.forEach(select => {
                const price = parseFloat(select.getAttribute('data-price'));
                const quantity = parseInt(select.value);
                total += price * quantity;
            });

            const totalPriceElement = document.getElementById('totalPrice');
            const hiddenTotalPriceElement = document.getElementById('hiddenTotalPrice');
            
            totalPriceElement.textContent = total.toFixed(2);
            hiddenTotalPriceElement.value = total.toFixed(2);
        }

        // Add event listeners to quantity selects
        document.querySelectorAll('.item-quantity').forEach(select => {
            select.addEventListener('change', calculateTotalPrice);
        });

        // Form submission validation
        document.getElementById('orderForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const firstName = document.getElementById('firstName');
            const lastName = document.getElementById('lastName');
            
            const quantityInputs = document.querySelectorAll('.item-quantity');
            const hasItems = Array.from(quantityInputs).some(select => select.value > 0);

            // Custom validation
            if (!hasItems) {
                alert('Please order at least one item.');
                return false;
            }

            if (!firstName.value.trim()) {
                firstName.setCustomValidity('Please enter your first name');
                firstName.reportValidity();
                return false;
            }

            if (!lastName.value.trim()) {
                lastName.setCustomValidity('Please enter your last name');
                lastName.reportValidity();
                return false;
            }

            // Calculate pickup time
            calculatePickupTime();

            // If all validations pass, submit the form
            event.target.submit();
        });

        // Initial calculations
        calculatePickupTime();
        calculateTotalPrice();
    </script>
</body>
</html>
<?php mysqli_close($connection); ?>