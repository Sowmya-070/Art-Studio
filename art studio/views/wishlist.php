<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';
include '../controllers/wishlist.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wishlist - Online Art Studio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../views/includes/sidebar.php'; ?>
    <?php include '../views/includes/topbar.php'; ?>

    <div class="pc-container">
        <div class="pc-content">
            <h2>Your Wishlist</h2>
            <div class="wishlist-list">
                <?php
                $wishlist = getWishlistItems($conn, $_SESSION['user_id']);
                while ($art = $wishlist->fetch_assoc()) {
                    echo "<div class='artwork-card'>";
                    echo "<img src='../uploads/artworks/" . $art['image'] . "' alt='" . $art['title'] . "'>";
                    echo "<h4>" . $art['title'] . "</h4>";
                    echo "<p>" . $art['description'] . "</p>";
                    echo "<p>Price: $" . $art['price'] . "</p>";
                    echo "<button onclick='removeFromWishlist(" . $art['id'] . ")'>Remove</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <?php include '../views/includes/footer.php'; ?>

    <script>
        function removeFromWishlist(artwork_id) {
            fetch('../controllers/wishlist.php', {
                method: 'POST',
                body: JSON.stringify({ remove_wishlist: true, artwork_id: artwork_id }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => alert(data.message))
            .then(() => location.reload());
        }
    </script>
</body>
</html>
