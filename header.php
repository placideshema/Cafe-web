<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Two Owls Cafe - Specialty Beverages & More</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            max-width: 800px;
            margin: 0 auto;
            background-color: #f4f0e6;
        }
        header {
            background-color: #4a4a4a;
            color: white;
            padding: 20px;
            border-bottom: 4px solid #8b4513;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        .logo img {
            height: 50px;
            margin-right: 10px;
        }
        .subtitle {
            font-size: 0.9em;
            margin-top: 8px;
        }
        .hours {
            font-size: 0.8em;
            margin-top: 4px;
        }
        .admin-link {
            color: #ffd700;
            text-decoration: none;
            margin-left: auto;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="owl_logo.png" alt="Two Owls Cafe">
            Two Owls Cafe
        </div>
        <div class="subtitle">Featuring Specialty Beverages & Cafe Favorites</div>
        <div class="hours">Hours: 11am - 10pm</div>
        <?php if(basename($_SERVER['PHP_SELF']) != 'show_orders.php'): ?>
            <a href="show_orders.php" class="admin-link">Admin</a>
        <?php endif; ?>
    </header>
</body>
</html>