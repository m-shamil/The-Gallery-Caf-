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

// Function to escape input data
function sanitizeInput($data, $conn) {
    return $conn->real_escape_string(trim($data));
}

// Handle order submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderItems = $_POST['items'] ?? [];
    $fullName = sanitizeInput($_POST['full_name'] ?? '', $conn);
    $email = sanitizeInput($_POST['email'] ?? '', $conn);
    $phoneNumber = sanitizeInput($_POST['phone_number'] ?? '', $conn);
    $specialRequests = sanitizeInput($_POST['special_requests'] ?? '', $conn);

    $itemsInserted = false;

    // Fetch food item information and insert into orders table
    foreach ($orderItems as $itemId => $item) {
        $quantity = (int)($item['quantity'] ?? 0);
        if ($quantity > 0) {
            $foodItemId = (int)$itemId;
            
            // Prepare SQL statements to fetch food details
            $queries = [
                "SELECT name, price FROM food_menu WHERE id_Srilanka = $foodItemId",
                "SELECT name, price FROM chinese_cuisines WHERE id_chinese = $foodItemId",
                "SELECT name, price FROM italy_cuisines WHERE id_italy = $foodItemId"
            ];

            $found = false;
            foreach ($queries as $query) {
                $foodResult = $conn->query($query);

                if ($foodResult && $foodResult->num_rows > 0) {
                    $foodRow = $foodResult->fetch_assoc();
                    $foodItem = sanitizeInput($foodRow['name'], $conn);
                    $price = (float)$foodRow['price'];

                    // Directly embed variables into the SQL query
                    $insertQuery = "INSERT INTO orders (food_item, full_name, email, phone_number, quantity, unit_price, special_requests, status) VALUES ('$foodItem', '$fullName', '$email', '$phoneNumber', $quantity, $price, '$specialRequests', 'Pending')";
                    
                    if ($conn->query($insertQuery) === TRUE) {
                        $itemsInserted = true;
                    } else {
                        die("Insert failed: " . $conn->error);
                    }
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                // Optionally handle the case where no food item is found
                // echo "No matching food item found for ID: $foodItemId<br>";
            }
        }
    }

    // Prepare HTML for pop-up messages
    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Order Status</title>";
    echo "<style>";
    echo "body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; }";
    echo ".popup-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000; }";
    echo ".popup { background: #fff; border-radius: 10px; padding: 30px; text-align: center; max-width: 600px; width: 90%; box-shadow: 0 8px 16px rgba(0,0,0,0.2); animation: fadeIn 0.5s; }";
    echo ".success { color: #4CAF50; font-size: 18px; font-weight: bold; }";
    echo ".error { color: #f44336; font-size: 18px; font-weight: bold; }";
    echo "button { background: #007bff; border: none; color: #fff; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-size: 16px; transition: background 0.3s; }";
    echo "button:hover { background: #0056b3; }";
    echo "@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }";
    echo "</style>";
    echo "</head>";
    echo "<body>";

    echo "<div class='popup-overlay'>";
    echo "<div class='popup'>";
    if ($itemsInserted) {
        echo "<p class='success'>Order placed successfully!</p>";
    } else {
        echo "<p class='error'>No items to place in the order.</p>";
    }
    echo "<button onclick='redirectToMenu()'>OK</button>";
    echo "</div>";
    echo "</div>";

    echo "<script>";
    echo "function redirectToMenu() {";
    echo "    window.location.href = 'index.html';";
    echo "}"; 
    echo "</script>";

    echo "</body>";
    echo "</html>";

    // Close connection
    $conn->close();
    exit();
}

// Redirect to the menu page if not a POST request
header("Location: index.html");
exit();
?>
