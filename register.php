<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "restaurant";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $email = $_POST['email'];

    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Username already exists.";
    } else {
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $user, $hashed_password, $email);
        if ($stmt->execute()) {
            $message = "Registration successful!";
            $success = true;
        } else {
            $message = "Error: " . $stmt->error;
        }
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
    <title>Registration Status</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; }
        .popup-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000; }
        .popup { background: #fff; border-radius: 10px; padding: 30px; text-align: center; max-width: 600px; width: 90%; box-shadow: 0 8px 16px rgba(0,0,0,0.2); animation: fadeIn 0.5s; }
        .success { color: #4CAF50; font-size: 18px; font-weight: bold; }
        .error { color: #f44336; font-size: 18px; font-weight: bold; }
        button { background: #007bff; border: none; color: #fff; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-size: 16px; transition: background 0.3s; }
        button:hover { background: #0056b3; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body>

<div class='popup-overlay'>
    <div class='popup'>
        <?php if ($success): ?>
            <p class='success'><?php echo $message; ?></p>
        <?php else: ?>
            <p class='error'><?php echo $message; ?></p>
        <?php endif; ?>
        <button onclick='redirectToMenu()'>OK</button>
    </div>
</div>

<script>
function redirectToMenu() {
    window.location.href = 'index.html'; // Change this to your desired redirection page
}
</script>

</body>
</html>
