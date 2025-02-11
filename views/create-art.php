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
            <a href="index.html"><img src="../paint-app/images/Paint.png" alt="logo"/></a>
            <h1>ART STUDIO</h1>
            <a href="#" title="Right click on canvas to Save drawing!" style="background-color: #FFFFFF; color: #000000; text-decoration: none; font-weight: 500;"><button id="btnSave">Save</button></a>
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
    </script>

<script src="../paint-app/js/CanvasScript.js"></script> 
   
</body>
</html>

