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

$error_message = "";  
$success_message = "";

// Handle actions for reservations and orders
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $action = $_POST['action'] ?? '';
    $type = $_POST['type'] ?? '';

    if ($type == 'reservation') {
        if ($action == 'confirm') {
            $sql = "UPDATE reservations SET status='Confirmed' WHERE id=?";
        } elseif ($action == 'modify') {
            $sql = "UPDATE reservations SET status='Pending' WHERE id=?";
        } elseif ($action == 'cancel') {
            $sql = "DELETE FROM reservations WHERE id=?";
        } elseif ($action == 'update') {
            $full_name = $_POST['full_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone_number = $_POST['phone_number'] ?? '';
            $reservation_date = $_POST['reservation_date'] ?? '';
            $reservation_time = $_POST['reservation_time'] ?? '';
            $guests = $_POST['guests'] ?? '';
            $special_requests = $_POST['special_requests'] ?? '';
            $sql = "UPDATE reservations SET full_name=?, email=?, phone_number=?, reservation_date=?, reservation_time=?, guests=?, special_requests=? WHERE id=?";
        } else {
            $error_message = "Invalid action";
        }
    } elseif ($type == 'order') {
        if ($action == 'confirm') {
            $sql = "UPDATE orders SET status='Confirmed' WHERE id=?";
        } elseif ($action == 'modify') {
            $sql = "UPDATE orders SET status='Pending' WHERE id=?";
        } elseif ($action == 'cancel') {
            $sql = "DELETE FROM orders WHERE id=?";
        } elseif ($action == 'update') {
            $food_item = $_POST['food_item'] ?? '';
            $full_name = $_POST['full_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone_number = $_POST['phone_number'] ?? '';
            $quantity = $_POST['quantity'] ?? '';
            $unit_price = $_POST['unit_price'] ?? '';
            $special_requests = $_POST['special_requests'] ?? '';
            $sql = "UPDATE orders SET food_item=?, full_name=?, email=?, phone_number=?, quantity=?, unit_price=?, special_requests=? WHERE id=?";
        } else {
            $error_message = "Invalid action";
        }
    } else {
        $error_message = "Invalid type";
    }

    if ($error_message == "" && isset($sql)) {
        if ($type == 'reservation' && $action == 'update') {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssi", $full_name, $email, $phone_number, $reservation_date, $reservation_time, $guests, $special_requests, $id);
        } elseif ($type == 'order' && $action == 'update') {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssi", $food_item, $full_name, $email, $phone_number, $quantity, $unit_price, $special_requests, $id);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
        }

        if ($stmt->execute()) {
            $success_message = "Action performed successfully!";
        } else {
            $error_message = "Error performing action: " . $conn->error;
        }
        $stmt->close();
    }
}

// SQL query to get orders with a valid status
$sql_orders = "
    SELECT id,
           food_item,
           full_name,
           email,
           phone_number,
           quantity,
           unit_price,
           (quantity * unit_price) AS total_amount,
           status,
           special_requests
    FROM orders
    WHERE status IN ('Pending', 'Confirmed', 'Modified')
";

$orders_result = $conn->query($sql_orders);
if (!$orders_result) {
    die("Error executing query: " . $conn->error);
}

$reservations_result = $conn->query("SELECT * FROM reservations");
if (!$reservations_result) {
    die("Error executing query: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('images/empty-wooden-table-night-restaurant-scene-with-blur_31965-621763_(1)-transformed.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: #f4f4f4; /* Light text color */
            margin: 0;
            padding: 0;
        }
        h1 {
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent black background */
            color: gold; /* Gold text */
            padding: 20px;
            margin: 0;
            text-align: center;
        }
        .section {
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(34, 34, 34, 0.8); /* Semi-transparent dark gray background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            max-width: 1200px;
        }
        .section h2 {
            margin-bottom: 20px;
            font-size: 26px;
            color: #FFD700; /* Gold text */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #555555; /* Darker border */
        }
        table th, table td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color:#222222; /* Dark gray background */
            color: gold; /* Gold text */
        }
        td {
            background-color: #333333; /* Darker background for cells */
            word-wrap: break-word;
        }
        button {
    background-color: #FFD700; /* Gold background */
    color: black; /* Black text */
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 4px;
    margin-bottom: 10px;
    transition: background-color 0.3s, box-shadow 0.3s;
    font-size: 14px;
    margin-right: 5px;
    width: 100px; /* Fixed width for equal box size */
    text-align: center; /* Ensures button text is centered */
}

button:hover {
    background-color: #e5c100; /* Lighter gold on hover */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

button:active {
    background-color: #ccac00; /* Even darker gold on active */
}
        }
        .message {
            margin-bottom: 20px;
        }
        .error-message {
            color: #dc3545; /* Error message color */
        }
        .success-message {
            color: #28a745; /* Success message color */
        }
        .update-form {
            margin-top: 20px;
            padding: 20px;
            background-color: #222222; /* Dark gray background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            display: none; /* Hide form initially */
        }
        .update-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #FFD700; /* Gold text */
        }
        .update-form input, .update-form button {
            width: calc(100% - 22px);
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #555555; /* Darker border */
            border-radius: 4px;
        }
        .update-form input:focus {
            border-color: #FFD700; /* Gold border on focus */
            outline: none;
        }
        .update-form button {
            background-color: #FFD700; /* Gold background */
            color: black; /* Black text */
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .update-form button:hover {
            background-color: #e5c100; /* Lighter gold on hover */
        }
    </style>
    <script>
        function showUpdateForm(id, fullName, email, phoneNumber, date, time, guests, specialRequests) {
            document.getElementById('update_id').value = id;
            document.getElementById('update_full_name').value = fullName;
            document.getElementById('update_email').value = email;
            document.getElementById('update_phone_number').value = phoneNumber;
            document.getElementById('update_reservation_date').value = date;
            document.getElementById('update_reservation_time').value = time;
            document.getElementById('update_guests').value = guests;
            document.getElementById('update_special_requests').value = specialRequests;
            document.querySelector('.update-form').style.display = 'block';
        }
    </script>
</head>
<body>
    <h1>Staff Dashboard</h1>

    <div class="section">
        <h2>Reservations</h2>
        <?php if ($error_message): ?>
            <div class="message error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="message success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Reservation Date</th>
                    <th>Reservation Time</th>
                    <th>Guests</th>
                    <th>Special Requests</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $reservations_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['reservation_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['reservation_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['guests']); ?></td>
                    <td><?php echo htmlspecialchars($row['special_requests']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <button onclick="showUpdateForm(
                            '<?php echo htmlspecialchars($row['id']); ?>',
                            '<?php echo htmlspecialchars($row['full_name']); ?>',
                            '<?php echo htmlspecialchars($row['email']); ?>',
                            '<?php echo htmlspecialchars($row['phone_number']); ?>',
                            '<?php echo htmlspecialchars($row['reservation_date']); ?>',
                            '<?php echo htmlspecialchars($row['reservation_time']); ?>',
                            '<?php echo htmlspecialchars($row['guests']); ?>',
                            '<?php echo htmlspecialchars($row['special_requests']); ?>'
                        )">Update</button>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <input type="hidden" name="type" value="reservation">
                            <button type="submit" name="action" value="confirm">Confirm</button>
                            <button type="submit" name="action" value="cancel">Cancel</button>
                            <button type="submit" name="action" value="modify">Modify</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="update-form">
            <h2>Update Reservation</h2>
            <form action="" method="post">
                <input type="hidden" id="update_id" name="id">
                <input type="hidden" name="type" value="reservation">
                <input type="hidden" name="action" value="update">
                <label for="update_full_name">Full Name:</label>
                <input type="text" id="update_full_name" name="full_name" required>
                <label for="update_email">Email:</label>
                <input type="email" id="update_email" name="email" required>
                <label for="update_phone_number">Phone Number:</label>
                <input type="text" id="update_phone_number" name="phone_number" required>
                <label for="update_reservation_date">Reservation Date:</label>
                <input type="date" id="update_reservation_date" name="reservation_date" required>
                <label for="update_reservation_time">Reservation Time:</label>
                <input type="time" id="update_reservation_time" name="reservation_time" required>
                <label for="update_guests">Guests:</label>
                <input type="number" id="update_guests" name="guests" required>
                <label for="update_special_requests">Special Requests:</label>
                <input type="text" id="update_special_requests" name="special_requests">
                <button type="submit">Update</button>
            </form>
        </div>
    </div>

    <div class="section">
        <h2>Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Food Item</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Special Requests</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $orders_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['food_item']); ?></td>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['unit_price']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['special_requests']); ?></td>
                    <td>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <input type="hidden" name="type" value="order">
                            <input type="hidden" name="action" value="update">
                            <button type="submit" name="action" value="confirm">Confirm</button>
                            <button type="submit" name="action" value="cancel">Cancel</button>
                            <button type="submit" name="action" value="modify">Modify</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>