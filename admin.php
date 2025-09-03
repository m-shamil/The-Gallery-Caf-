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

$cuisine_type = isset($_POST['cuisine_type']) ? trim($_POST['cuisine_type']) : 'food_menu';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Food Menu Management
    if (isset($_POST['add_food']) || isset($_POST['update_food']) || isset($_POST['delete_food'])) {
        $name = trim($_POST['food_name']);
        $description = trim($_POST['food_description']);
        $price = trim($_POST['food_price']);
        $id = trim($_POST['food_id']);
        $imagePath = '';

        if (isset($_FILES['food_image']) && $_FILES['food_image']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['food_image']['tmp_name'];
            $fileName = basename($_FILES['food_image']['name']);
            $uploadDir = 'uploads/';
            $imagePath = $uploadDir . $fileName;

            if (!move_uploaded_file($tmpName, $imagePath)) {
                $error_message = "Failed to upload image.";
            }
        }

        if (empty($name) || empty($description) || empty($price)) {
            $error_message = "Please enter all fields to manage the food item.";
        } else {
            $table_name = $cuisine_type;
            $id_column = '';

            switch ($cuisine_type) {
                case 'chinese_cuisines':
                    $id_column = 'id_chinese';
                    break;
                case 'italy_cuisines':
                    $id_column = 'id_italy';
                    break;
                case 'food_menu':
                    $id_column = 'id_Srilanka';
                    break;
            }

            if (isset($_POST['add_food'])) {
                $sql = "INSERT INTO $table_name (name, description, price, image) VALUES ('$name', '$description', '$price', '$imagePath')";
                if ($conn->query($sql) === TRUE) {
                    $success_message = "Food item added successfully!";
                } else {
                    $error_message = "Error adding food item: " . $conn->error;
                }
            } elseif (isset($_POST['update_food'])) {
                if (empty($id)) {
                    $error_message = "Please enter an ID to update a food item.";
                } else {
                    $sql = "UPDATE $table_name SET name='$name', description='$description', price='$price', image='$imagePath' WHERE $id_column='$id'";
                    if ($conn->query($sql) === TRUE) {
                        if ($conn->affected_rows > 0) {
                            $success_message = "Food item updated successfully!";
                        } else {
                            $error_message = "Invalid ID or no changes made.";
                        }
                    } else {
                        $error_message = "Error updating food item: " . $conn->error;
                    }
                }
            } elseif (isset($_POST['delete_food'])) {
                if (empty($id)) {
                    $error_message = "Please enter an ID to delete a food item.";
                } else {
                    $sql = "DELETE FROM $table_name WHERE $id_column='$id'";
                    if ($conn->query($sql) === TRUE) {
                        if ($conn->affected_rows > 0) {
                            $success_message = "Food item deleted successfully!";
                        } else {
                            $error_message = "Invalid ID. No food item deleted.";
                        }
                    } else {
                        $error_message = "Error deleting food item: " . $conn->error;
                    }
                }
            }
        }
    }

    // User Management
    elseif (isset($_POST['add_user'])) {
        $username = trim($_POST['user_name']);
        $password = trim($_POST['user_password']);
        $email = trim($_POST['user_email']);

        if (empty($username) || empty($password) || empty($email)) {
            $error_message = "Please enter all fields to add a new user.";
        } else {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password_hashed', '$email')";
            if ($conn->query($sql) === TRUE) {
                $success_message = "User added successfully!";
            } else {
                $error_message = "Error adding user: " . $conn->error;
            }
        }
    } elseif (isset($_POST['update_user'])) {
        $id = trim($_POST['user_id']);
        $username = trim($_POST['user_name']);
        $password = trim($_POST['user_password']);
        $email = trim($_POST['user_email']);

        if (empty($id)) {
            $error_message = "Please enter an ID to update a user.";
        } else {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username='$username', password='$password_hashed', email='$email' WHERE id='$id'";
            if ($conn->query($sql) === TRUE) {
                if ($conn->affected_rows > 0) {
                    $success_message = "User updated successfully!";
                } else {
                    $error_message = "Invalid ID or no changes made.";
                }
            } else {
                $error_message = "Error updating user: " . $conn->error;
            }
        }
    } elseif (isset($_POST['delete_user'])) {
        $id = trim($_POST['user_id']);

        if (empty($id)) {
            $error_message = "Please enter an ID to delete a user.";
        } else {
            $sql = "DELETE FROM users WHERE id='$id'";
            if ($conn->query($sql) === TRUE) {
                if ($conn->affected_rows > 0) {
                    $success_message = "User deleted successfully!";
                } else {
                    $error_message = "Invalid ID. No user deleted.";
                }
            } else {
                $error_message = "Error deleting user: " . $conn->error;
            }
        }
    }
}

$food_menu_result = $conn->query("SELECT * FROM $cuisine_type");
$users_result = $conn->query("SELECT * FROM users");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('images/empty-wooden-table-night-restaurant-scene-with-blur_31965-621763_(1)-transformed.jpeg'); /* Background image URL */
            background-size: cover; /* Cover the entire background */
            background-position: center; /* Center the image */
            background-attachment: fixed; /* Fix the background image */
            color: #f4f4f4; /* Light text color */
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .admin-section {
            background-color: rgba(28, 28, 28, 0.8); /* Dark gray background with opacity */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
            padding: 20px;
        }
        .admin-section h2 {
            margin: 0 0 20px;
            font-size: 28px;
            color: #ffd700; /* Gold color */
            border-bottom: 2px solid #ffd700; /* Gold underline */
            padding-bottom: 10px;
        }
        .admin-section form {
            margin-bottom: 20px;
        }
        .admin-section form select,
        .admin-section form input[type="text"],
        .admin-section form input[type="number"],
        .admin-section form input[type="email"],
        .admin-section form input[type="password"],
        .admin-section form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #333; /* Dark border */
            border-radius: 4px;
            background-color: #2e2e2e; /* Dark form background */
            color: #f4f4f4; /* Light text color */
            box-sizing: border-box;
        }
        .admin-section form input[type="submit"] {
            padding: 10px 20px;
            background-color: #ffd700; /* Gold color */
            color: #000000; /* Dark text color */
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .admin-section form input[type="submit"]:hover {
            background-color: #e5c100; /* Slightly darker gold for hover */
        }
        .admin-section table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .admin-section table th,
        .admin-section table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #333; /* Dark border */
        }
        .admin-section table th {
            background-color: #1e1e1e; /* Darker gray background for headers */
            color: #ffd700; /* Gold color */
            font-weight: bold;
        }
        .admin-section table td {
            background-color: #2e2e2e; /* Darker gray background for table cells */
            color: #f4f4f4; /* Light text color */
        }
        .admin-section table img {
            max-width: 100px;
            border-radius: 4px;
        }
        .error-message {
            color: #ff4d4d; /* Error color */
            margin-bottom: 20px;
            font-weight: bold;
        }
        .success-message {
            color: #4caf50; /* Success color */
            margin-bottom: 20px;
            font-weight: bold;
        }
        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent black background */
            color: #f4f4f4; /* Light text color */
        }
        .nav a {
            color: #ffd700; /* Gold color */
            text-decoration: none;
            padding: 10px;
            font-size: 18px;
        }
        .nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="nav">
        <div class="container">
         <h1>Admin Dashboard</h1>
        </div>
    </div>

    <div class="container">

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Food Menu Management -->
        <div class="admin-section">
            <h2>Food Menu Management</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <select name="cuisine_type">
                    <option value="chinese_cuisines" <?php echo ($cuisine_type == 'chinese_cuisines') ? 'selected' : ''; ?>>Chinese cuisines</option>
                    <option value="italy_cuisines" <?php echo ($cuisine_type == 'italy_cuisines') ? 'selected' : ''; ?>>Italian cuisines</option>
                    <option value="food_menu" <?php echo ($cuisine_type == 'food_menu') ? 'selected' : ''; ?>>Sri Lankan cuisines</option>
                </select>
                <input type="number" name="food_id" id="food_id" placeholder="ID (Optional)">
                <input type="text" name="food_name" id="food_name" placeholder="Name">
                <input type="text" name="food_description" id="food_description" placeholder="Description">
                <input type="number" name="food_price" id="food_price" placeholder="Price">
                <input type="file" name="food_image" id="food_image" placeholder="Image (Optional)">
                <input type="submit" name="add_food" value="Add">
                <input type="submit" name="update_food" value="Update">
                <input type="submit" name="delete_food" value="Delete">
            </form>

            <!-- Food Menu Table -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $food_menu_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_chinese'] ?? $row['id_italy'] ?? $row['id_Srilanka']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td>
                            <?php if (!empty($row['image']) && file_exists($row['image'])): ?>
                                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <?php else: ?>
                                <img src="path/to/default/image.jpg" alt="No image available">
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- User Management -->
        <div class="admin-section">
            <h2>User Management</h2>
            <form method="POST" action="">
                <input type="number" name="user_id" id="user_id" placeholder="ID (Optional)">
                <input type="text" name="user_name" id="user_name" placeholder="Username">
                <input type="password" name="user_password" id="user_password" placeholder="Password">
                <input type="email" name="user_email" id="user_email" placeholder="Email">
                <input type="submit" name="add_user" value="Add">
                <input type="submit" name="update_user" value="Update">
                <input type="submit" name="delete_user" value="Delete">
            </form>

            <!-- Users Table -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
