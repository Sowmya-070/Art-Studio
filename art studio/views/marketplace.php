<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';
include '../controllers/marketplace.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Marketplace - Online Art Studio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        function previewImage(src) {
            let modal = document.getElementById('imagePreviewModal');
            let modalImg = document.getElementById('previewImage');
            modal.style.display = "flex";
            modalImg.src = src;
        }
        function closePreview() {
            document.getElementById('imagePreviewModal').style.display = "none";
        }
    </script>
    <style>
        .thumbnail {
            width: 100px;
            height: auto;
            cursor: pointer;
            border-radius: 5px;
            transition: transform 0.2s;
        }
        .thumbnail:hover {
            transform: scale(1.1);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            max-width: 50%;
            max-height: 50%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            background-color: white;
            padding: 10px;
        }
        .close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
            background: rgba(0,0,0,0.5);
            padding: 5px 10px;
            border-radius: 5px;
        }
        .close:hover {
            background: rgba(0,0,0,0.8);
        }
    </style>
</head>
<body>
    <?php include '../views/includes/sidebar.php'; ?>
    <?php include '../views/includes/topbar.php'; ?>

    <div class="pc-container">
        <div class="pc-content">
            <h2>Marketplace</h2>

            <!-- Artists: Upload Artwork -->
            <?php if ($_SESSION['user_role'] == 'artist') { ?>
                <h3>Upload Artwork</h3>
                <form method="post" action="../controllers/marketplace.php" enctype="multipart/form-data">
                    <input type="text" name="title" placeholder="Title" required>
                    <textarea name="description" placeholder="Description" required></textarea>
                    <input type="file" name="image" required>
                    <input type="number" name="price" placeholder="Price" required>
                    <button type="submit" name="upload">Upload</button>
                </form>
            <?php } ?>

            <!-- Display Artwork -->
            <h3>Available Artworks</h3>
            <div class="artwork-list">
                <?php
                $artworks = getArtworks($conn);
                while ($art = $artworks->fetch_assoc()) {
                    echo "<div class='artwork-card'>";
                    echo "<img class='thumbnail' src='../uploads/artworks/" . $art['image'] . "' alt='" . $art['title'] . "' onclick='previewImage(" . '"../uploads/artworks/' . $art['image'] . '"' . ")'>";
                    echo "<h4>" . $art['title'] . "</h4>";
                    echo "<p>" . $art['description'] . "</p>";
                    echo "<p>By: " . $art['artist_name'] . "</p>";
                    echo "<p>Price: $" . $art['price'] . "</p>";
                    if ($_SESSION['user_role'] == 'buyer') {
                        echo "<button>Buy Now</button>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <div id="imagePreviewModal" class="modal">
        <span class="close" onclick="closePreview()">&times;</span>
        <div class="modal-content">
            <img id="previewImage" style="width: 100%; height: auto;">
        </div>
    </div>

    <?php include '../views/includes/footer.php'; ?>
</body>
</html>