<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Sri Lankan cuisines from the database
$sql = "SELECT id_Srilanka, name, description, price, image FROM food_menu";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menu</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #111;
            color: #fff;
            margin: 0;
            padding: 0;
            background-image: url('images/empty-wooden-table-night-restaurant-scene-with-blur_31965-621763_(1)-transformed.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .menu-section {
            max-width: 900px;
            margin: auto;
            padding: 30px;
            background-color: rgba(30, 30, 30, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            background-image: url('images/desktop-wallpaper-restaurant-menu-menu.jpg'); /* New background image */
            background-size: cover;
            background-position: center;
        }

        .menu-section h2, .menu-section h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #ffdd57;
        }

        .search-container {
            text-align: center;
            margin-bottom: 30px;
        }

        #search-box {
            width: 85%;
            padding: 15px;
            font-size: 18px;
            border: 2px solid #ffdd57;
            border-radius: 30px;
            background-color: #222;
            color: #fff;
            transition: width 0.3s ease, border-color 0.3s ease;
        }

        #search-box::placeholder {
            color: #bbb;
            opacity: 0.8;
        }

        #search-box:focus {
            outline: none;
            width: 95%;
            border-color: #ffc107;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            background-color: rgba(51, 51, 51, 0.8);
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, background-color 0.3s ease;
            justify-content: space-between;
            background-image: url('images/background-pattern-black-gold-hd-wallpaper-preview.jpg');
            background-size: cover;
            background-position: center;
        }

        .menu-item img {
            border-radius: 8px;
            width: 120px;
            height: 80px;
            margin-right: 20px;
            transition: transform 0.3s ease;
        }

        .menu-item img:hover {
            transform: scale(1.05);
        }

        .menu-item h3 {
            margin: 0;
            font-size: 26px;
            font-weight: 600;
        }

        .menu-item p {
            margin: 5px 0 0;
            font-size: 15px;
            color: #bbb;
        }

        .menu-item .price {
            font-size: 20px;
            color: #ffdd57;
            font-weight: 600;
        }

        .menu-item .quantity {
            width: 60px;
            padding: 5px;
            border: 1px solid #ffdd57;
            border-radius: 5px;
            background-color: #222;
            color: #fff;
            text-align: center;
            margin-right: 10px;
        }

        .menu-item .actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .menu-item .actions .preorder {
            background-color: #ffdd57;
            color: #111;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .menu-item .actions .preorder:hover {
            background-color: #ffca28;
        }

        .menu-item:hover {
            transform: scale(1.03);
            background-color: #444;
        }

        .menu-item .details {
            flex-grow: 1;
        }

        #total-amount {
            text-align: center;
            font-size: 24px;
            color: #ffdd57;
            margin-top: 20px;
        }

        .user-info {
            margin-top: 20px;
            color: #fff;
        }

        .user-info label {
            display: block;
            margin-bottom: 5px;
        }

        .user-info input {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ffdd57;
            border-radius: 5px;
            background-color: #222;
            color: #fff;
        }

        .special-requests {
            margin-top: 20px;
            color: #fff;
        }

        .special-requests textarea {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ffdd57;
            border-radius: 5px;
            background-color: #222;
            color: #fff;
            resize: vertical;
            height: 100px;
        } 
        .submit-button {
    background-color: #ffdd57;
    border: none;
    padding: 15px 30px;
    color: #111;
    font-size: 18px;
    font-weight: 600;
    border-radius: 5px;
    cursor: pointer;
    display: block;
    margin: 20px auto;
    transition: background-color 0.3s ease;
}

.submit-button:hover {
    background-color: #ffca28;
}

    </style>
    <script>
        function searchMenu() {
            let input = document.getElementById('search-box').value.toLowerCase();
            let menuItems = document.querySelectorAll('.menu-item');

            menuItems.forEach(function(item) {
                let name = item.querySelector('.menu-name').innerText.toLowerCase();
                item.style.display = name.includes(input) ? "flex" : "none";
            });
        }

        function calculateTotalAmount() {
            let total = 0;
            document.querySelectorAll('.menu-item').forEach(function(item) {
                let quantity = parseInt(item.querySelector('.quantity').value) || 0;
                let price = parseFloat(item.querySelector('.price').getAttribute('data-price')) || 0;
                total += quantity * price;
            });
            document.getElementById('total-amount').innerText = 'Total Amount: LKR ' + total.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.quantity').forEach(function(input) {
                input.addEventListener('input', calculateTotalAmount);
            });
        });
    </script>
</head>
<body>
    <div class="menu-section">
        <h1>Sri Lankan Cuisines</h1>
        <h2>Lunch & Dinner</h2>
       

        <div class="search-container">
            <input type="text" id="search-box" placeholder="Search for food..." onkeyup="searchMenu()">
        </div>

        <form action="submit_order.php" method="post" id="order-form">
            <div id="menu-items">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='menu-item'>";
                        if (!empty($row["image"])) {
                            echo "<img src='" . htmlspecialchars($row["image"]) . "' alt='" . htmlspecialchars($row["name"]) . "'>";
                        }
                        echo "<div class='details'>";
                        echo "<h3 class='menu-name'>" . htmlspecialchars($row["name"]) . "</h3>";
                        echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
                        echo "</div>";
                        echo "<div class='actions'>";
                        echo "<span class='price' data-price='" . htmlspecialchars($row["price"]) . "'>LKR " . htmlspecialchars($row["price"]) . "</span><br>";
                        echo "<input type='number' name='items[" . htmlspecialchars($row["id_Srilanka"]) . "][quantity]' class='quantity' min='0' value='0'>";
                        echo "<input type='hidden' name='items[" . htmlspecialchars($row["id_Srilanka"]) . "][id]' value='" . htmlspecialchars($row["id_Srilanka"]) . "'>";
                        echo "<input type='hidden' name='items[" . htmlspecialchars($row["id_Srilanka"]) . "][name]' value='" . htmlspecialchars($row["name"]) . "'>";
                        echo "<input type='hidden' name='items[" . htmlspecialchars($row["id_Srilanka"]) . "][price]' value='" . htmlspecialchars($row["price"]) . "'>";
                        echo "<input type='hidden' name='items[" . htmlspecialchars($row["id_Srilanka"]) . "][description]' value='" . htmlspecialchars($row["description"]) . "'>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p style='text-align:center; color: #ffdd57;'>No items found in the menu.</p>";
                }
                ?>
            </div>
            <!-- User information fields -->
            <div class="user-info">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" required>
            </div>

            <!-- Special Requests -->
            <div class="special-requests">
                <label for="special_requests">Special Requests:</label>
                <textarea id="special_requests" name="special_requests" placeholder="Any special requests or instructions?"></textarea>
            </div>

            <h3 id="total-amount">Total Amount: LKR 0.00</h3>
            <button type="submit" class="submit-button">Place Order</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
