<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO reservations (full_name, email, phone_number, reservation_date, reservation_time, guests, special_requests) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssis", $full_name, $email, $phone_number, $reservation_date, $reservation_time, $guests, $special_requests);

$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$reservation_date = $_POST['date'];
$reservation_time = $_POST['time'];
$guests = $_POST['guests'];
$special_requests = $_POST['special_requests'];

$reservationSuccessful = $stmt->execute();
$stmt->close();
$conn->close();

echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Reservation Status</title>";
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
if ($reservationSuccessful) {
    echo "<p class='success'>Reservation successful!</p>";
} else {
    echo "<p class='error'>Error: " . $stmt->error . "</p>";
}
echo "<button onclick='redirectToHome()'>OK</button>";
echo "</div>";
echo "</div>";

echo "<script>";
echo "function redirectToHome() {";
echo "    window.location.href = 'index.html';";
echo "}"; 
echo "</script>";

echo "</body>";
echo "</html>";
?>
