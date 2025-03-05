<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';
include '../controllers/marketplace.php';
// Fetch username for unique file naming
$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT name FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);
$username = strtolower(str_replace(' ', '_', $user['name']));
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width,initial-scale=1.0'>
        <title>Drawing Window</title>
        <link rel='stylesheet' type='text/css' href='../paint-app/css/canvaStyle.css'>
        <script src="https://kit.fontawesome.com/28e5fa47e4.js" crossorigin="anonymous"></script>
         
    </head>

<body>
    <header>
        <div class="header" id="header">
            <img src="../paint-app/images/Paint.png" alt="logo"/>
            <h1>ART STUDIO</h1>
            <!-- Back Button -->
            
            <button id="btnSave" onclick=goBack()
   title="Right-click on canvas to Save drawing!" 
   style="background-color: #FFFFFF; color: #000000; text-decoration: none; font-weight: 500;">
   🔙Back
</button>
        
            <button id="btnSave" onclick=saveArtwork()
   title="Right-click on canvas to Save drawing!" 
   style="background-color: #FFFFFF; color: #000000; text-decoration: none; font-weight: 500;">
   Save
</button>
        </div>
    </header>
       <canvas id="myCanvas"></canvas>
       
        <div class="wrapper" id="wrapper">
            <div class="icon color">
                <div class="tooltip">
                    Select
                </div>
                <span><i><input type="color" id="colorChange"></i></span>
            </div>

                <div class="tooltip">
                    <h4>Pen Size</h4>
                </div>
                <span><i><input type="range" id="penSize" step="2" min="2" max="150" value="5"></i></span>
    
         <div class="icon Pencil">
            <div class="tooltip">
                Pencil
            </div>
            <button  id="btnPencil">
            <span><i class="fa fa-pencil-alt"></i></span>
             </button>
        </div>
        
        <div class="icon Bucket">
            <div class="tooltip">
                Fill
            </div>
            <button  id="btnBucket">
            <span><i class="fa fa-fill-drip"></i></span>
             </button>
        </div>

        <div class="icon Eraser">
            <div class="tooltip">
                Eraser
            </div>
            <button  id="btnEraser">
            <span><i class="fa fa-eraser"></i></span>
             </button>
        </div>
        
        <div class="icon Clear">
            <div class="tooltip">
                Clear
            </div>
            <button  id="btnClear">
            <span><i class="fa fa-broom"></i></span>
             </button>
        </div>

        <div class="icon Undo">
            <div class="tooltip">
                Undo
            </div>
             <button  id="btnUndo">
            <span><i class="fa fa-undo"></i></span>
             </button>
        </div>
        </div>
        <script>
        function saveArtwork() {
    let canvas = document.getElementById('myCanvas');
    let ctx = canvas.getContext('2d');

    // Create a temporary canvas to merge background and drawing
    let tempCanvas = document.createElement('canvas');
    let tempCtx = tempCanvas.getContext('2d');
    
    tempCanvas.width = canvas.width;
    tempCanvas.height = canvas.height;

    // Fill the background with white
    tempCtx.fillStyle = "#FFFFFF"; 
    tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);

    // Draw the existing canvas on top
    tempCtx.drawImage(canvas, 0, 0);

    // Convert to PNG with the white background
    let artwork = tempCanvas.toDataURL('image/png');

    fetch('../controllers/save_art.php', {
        method: 'POST',
        body: JSON.stringify({ image: artwork }),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        console.log("Response:", data);
        if (data.success) {
            alert("✅ Artwork saved successfully!");
        } else {
            alert("❌ Error: " + data.message);
        }
    })
    .catch(error => console.error('Save Error:', error));
}


function goBack() {
        window.location.href = "../views/dashboard.php"; // Redirect to the dashboard page
    }





    </script>

<script src="../paint-app/js/CanvasScript.js"></script> 
   
</body>
</html>

