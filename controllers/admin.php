<?php
session_start();
include '../config/db.php';

// Ensure Admin Access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../views/dashboard.php");
    exit();
}

// Fetch All Users
function getAllUsers($conn) {
    $sql = "SELECT * FROM users";
    return $conn->query($sql);
}

// Delete User
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $sql = "DELETE FROM users WHERE id = '$user_id'";
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "User Deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Error"]);
    }
}

// Fetch All Artworks
function getAllArtworks($conn) {
    $sql = "SELECT artworks.*, users.name AS artist_name FROM artworks JOIN users ON artworks.artist_id = users.id";
    return $conn->query($sql);
}

// Delete Artwork
if (isset($_POST['delete_artwork'])) {
    $artwork_id = $_POST['artwork_id'];
    $sql = "DELETE FROM artworks WHERE id = '$artwork_id'";
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "Artwork Deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Error"]);
    }
}

// Fetch Reported Artworks
function getReportedArtworks($conn) {
    $sql = "SELECT reported_artworks.*, artworks.title, users.name AS reported_by 
            FROM reported_artworks 
            JOIN artworks ON reported_artworks.artwork_id = artworks.id
            JOIN users ON reported_artworks.user_id = users.id";
    return $conn->query($sql);
}

// Remove Reported Artwork
if (isset($_POST['delete_reported_artwork'])) {
    $artwork_id = $_POST['artwork_id'];
    $sql = "DELETE FROM artworks WHERE id = '$artwork_id'";
    $sql_report = "DELETE FROM reported_artworks WHERE artwork_id = '$artwork_id'";
    
    if ($conn->query($sql) && $conn->query($sql_report)) {
        echo json_encode(["status" => "success", "message" => "Reported Artwork Removed"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Error"]);
    }
}
?>
