<?php
session_start();
include '../config/db.php';

// Add to Wishlist
if (isset($_POST['add_wishlist'])) {
    $user_id = $_SESSION['user_id'];
    $artwork_id = $_POST['artwork_id'];

    $sql = "INSERT INTO wishlist (user_id, artwork_id) VALUES ('$user_id', '$artwork_id')";
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "Added to Wishlist"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Error"]);
    }
}

// Remove from Wishlist
if (isset($_POST['remove_wishlist'])) {
    $user_id = $_SESSION['user_id'];
    $artwork_id = $_POST['artwork_id'];

    $sql = "DELETE FROM wishlist WHERE user_id = '$user_id' AND artwork_id = '$artwork_id'";
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "Removed from Wishlist"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Error"]);
    }
}

// Fetch Wishlist Items
function getWishlistItems($conn, $user_id) {
    $sql = "SELECT artworks.* FROM wishlist 
            JOIN artworks ON wishlist.artwork_id = artworks.id 
            WHERE wishlist.user_id = $user_id";
    return $conn->query($sql);
}
?>
