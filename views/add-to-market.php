<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Paint App - Online Art Studio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../paint-app/js/canvas.js"></script>
</head>
<body>
    <?php include '../views/includes/sidebar.php'; ?>
    <?php include '../views/includes/topbar.php'; ?>

    <div class="pc-container">
        <div class="pc-content">
            <h2>Create Art</h2>
            
            <!-- Paint App Canvas -->
            <div id="canvas-container">
                <canvas id="paintCanvas"></canvas>
                <input type="file" id="uploadStamp" accept="image/*">
                <button onclick="saveArtwork()">Save Artwork</button>
                <button onclick="loadSavedArtwork()">Load Saved Artwork</button>
            </div>
        </div>
    </div>

    <?php include '../views/includes/footer.php'; ?>

    <script>
        function saveArtwork() {
            let canvas = document.getElementById('paintCanvas');
            let artwork = canvas.toDataURL('image/png');

            fetch('../controllers/save_art.php', {
                method: 'POST',
                body: JSON.stringify({ image: artwork }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => alert(data.message));
        }

        function loadSavedArtwork() {
            fetch('../controllers/load_art.php')
            .then(response => response.json())
            .then(data => {
                if (data.image) {
                    let canvas = document.getElementById('paintCanvas');
                    let ctx = canvas.getContext('2d');
                    let img = new Image();
                    img.onload = function () { ctx.drawImage(img, 0, 0); };
                    img.src = data.image;
                }
            });
        }
    </script>
</body>
</html>
