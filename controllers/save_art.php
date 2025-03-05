<?php
session_start();
include '../config/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

// Debugging Log
file_put_contents("../logs/save_art_log.txt", json_encode($data) . PHP_EOL, FILE_APPEND);

if (!$data || !isset($data['image'])) {
    echo json_encode(["success" => false, "message" => "No image data received"]);
    exit();
}

$imageData = $data['image'];
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

// Fetch username for unique file naming
$result = mysqli_query($conn, "SELECT name FROM users WHERE id='$userId'");
$user = mysqli_fetch_assoc($result);
$username = strtolower(str_replace(' ', '_', $user['name']));

// Validate and decode base64 image
if (strpos($imageData, 'data:image/png;base64,') !== false) {
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $imageDecoded = base64_decode($imageData);
} else {
    echo json_encode(["success" => false, "message" => "Invalid image format"]);
    exit();
}

if (!$imageDecoded) {
    echo json_encode(["success" => false, "message" => "Image decoding failed"]);
    exit();
}

// Ensure the directory exists
$filePath = '../uploads/artworks/';
if (!file_exists($filePath)) {
    mkdir($filePath, 0777, true);
}

// Generate unique filename
$fileName = $username . "_" . time() . ".jpg";
$fileFullPath = $filePath . $fileName;

// Convert PNG to JPG and Save
$image = imagecreatefromstring($imageDecoded);
if (!$image) {
    echo json_encode(["success" => false, "message" => "Error creating image"]);
    exit();
}

if (imagejpeg($image, $fileFullPath, 90)) {
    imagedestroy($image);

    // Save to database
    $query = "INSERT INTO saveartwork (user_id, image_name, image_path) VALUES ('$userId', '$fileName', '$fileFullPath')";
    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true, "message" => "Artwork saved successfully!", "file" => $fileName]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
    }
} else {
    imagedestroy($image);
    echo json_encode(["success" => false, "message" => "Failed to save image"]);
}
?>
