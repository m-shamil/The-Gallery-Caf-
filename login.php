<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "restaurant"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

 
    if ($user === 'admin' && $pass === '123') {
        $_SESSION['username'] = $user;
        $_SESSION['role'] = 'admin';
        header("Location: admin.php");
        exit;
    } elseif ($user === 'staff' && $pass === '123') {
        $_SESSION['username'] = $user;
        $_SESSION['role'] = 'staff';
        header("Location: staff.php");
        exit;
    }

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($pass, $row['password'])) {
            $_SESSION['username'] = $user;
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: admin.php");
            } elseif ($row['role'] == 'staff') {
                header("Location: staff.php");
            } else {
                header("Location: index.html");
            }
            exit;
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url(images/hero-slider-3.jpg);

        }
        .container {
            width: 400px;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Error</h2>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <a href="login.html">Go back to login</a>
    </div>
</body>
</html>
