<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

$artist_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$image_data = $data['image'];

// Convert Base64 image to PNG file
$image_name = "art_" . time() . ".png";
$image_path = "../uploads/artworks/" . $image_name;
file_put_contents($image_path, base64_decode(explode(",", $image_data)[1]));

// Insert into database
$sql = "INSERT INTO artworks (title, description, image, price, artist_id) VALUES ('Untitled', 'Created using Paint App', '$image_name', '0.00', '$artist_id')";
if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "Artwork saved!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error!"]);
}
?>
