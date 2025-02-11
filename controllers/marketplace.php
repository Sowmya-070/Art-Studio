<?php
session_start();
include '../config/db.php';

// Upload Artwork
if (isset($_POST['upload'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $artist_id = $_SESSION['user_id'];

    // Handle Image Upload
    $target_dir = "../uploads/artworks/";
    $image = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image;
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Insert into Database
    $sql = "INSERT INTO artworks (title, description, image, price, artist_id) VALUES ('$title', '$description', '$image', '$price', '$artist_id')";
    if ($conn->query($sql)) {
        header("Location: ../views/marketplace.php");
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch Artwork Listings
function getArtworks($conn) {
    $sql = "SELECT artworks.*, users.name AS artist_name FROM artworks JOIN users ON artworks.artist_id = users.id";
    return $conn->query($sql);
}

// Fetch Specific Artwork (for viewing)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM artworks WHERE id = $id";
    $result = $conn->query($sql);
    echo json_encode($result->fetch_assoc());
}
?>
