<?php
session_start();
require_once 'db.php';
require_once 'header.php';
require_once 'functions.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>

<style>
.games-container {
    max-width: 100%;
    margin: 20px auto;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background: #f9f9f9;
    text-align: center;
}
.game-box {
    margin: 20px 0;
    padding: 15px;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
button {
    padding: 10px 15px;
    margin: 5px;
    border: none;
    border-radius: 5px;
    background: #007bff;
    color: white;
    cursor: pointer;
    font-size: 16px;
}
button:hover {
    background: #0056b3;
}
#gameCanvas {
    width: 100%;
    max-width: 400px;
    height: auto;
    border:1px solid #000;
}
#replay-btn {
    display: none;
}
</style>
<center>
<div class="game-box">
    <h3>BLOCS 🎮 | AHN GAMES</h3>
    <canvas id="gameCanvas" width="400" height="500"></canvas>
    <p id="game-status" style="text-align:center; font-weight:bold;"></p>
    <button id="replay-btn">🔄 Rejouer</button>
    <div style="text-align:center; margin-top:10px;">
        <button id="btn-left">◀️ Gauche</button>
        <button id="btn-right">▶️ Droite</button>
    </div>
</div>
</center>
<script>
const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

let player, obstacles, gameOver;

// Réinitialiser le jeu
function initGame() {
    player = { x: 180, y: 450, width: 40, height: 40, speed: 5 };
    obstacles = [];
    gameOver = false;
    document.getElementById("game-status").textContent = "";
    document.getElementById("replay-btn").style.display = "none";
    gameLoop();
}

// Clavier
let keys = {};
document.addEventListener("keydown", e => keys[e.key] = true);
document.addEventListener("keyup", e => keys[e.key] = false);

// Touch / boutons
let moveLeft = false, moveRight = false;
function startLeft(){ moveLeft = true; }
function stopLeft(){ moveLeft = false; }
function startRight(){ moveRight = true; }
function stopRight(){ moveRight = false; }

const btnLeft = document.getElementById("btn-left");
const btnRight = document.getElementById("btn-right");

btnLeft.addEventListener("mousedown", startLeft);
btnLeft.addEventListener("mouseup", stopLeft);
btnLeft.addEventListener("touchstart", startLeft);
btnLeft.addEventListener("touchend", stopLeft);

btnRight.addEventListener("mousedown", startRight);
btnRight.addEventListener("mouseup", stopRight);
btnRight.addEventListener("touchstart", startRight);
btnRight.addEventListener("touchend", stopRight);

// Boucle du jeu
function gameLoop() {
    if (gameOver) return;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Déplacement joueur
    if ((keys["ArrowLeft"] || moveLeft) && player.x > 0) player.x -= player.speed;
    if ((keys["ArrowRight"] || moveRight) && player.x < canvas.width - player.width) player.x += player.speed;

    // Joueur
    ctx.fillStyle = "blue";
    ctx.fillRect(player.x, player.y, player.width, player.height);

    // Obstacles
    if (Math.random() < 0.03) {
        obstacles.push({ x: Math.random() * (canvas.width - 40), y: 0, width: 40, height: 40, speed: 3 });
    }

    obstacles.forEach(o => {
        o.y += o.speed;
        ctx.fillStyle = "red";
        ctx.fillRect(o.x, o.y, o.width, o.height);

        // Collision
        if (o.x < player.x + player.width &&
            o.x + o.width > player.x &&
            o.y < player.y + player.height &&
            o.y + o.height > player.y) {
            endGame();
        }
    });

    // Filtrer obstacles sortis
    obstacles = obstacles.filter(o => o.y < canvas.height);

    requestAnimationFrame(gameLoop);
}

// Fin du jeu
function endGame() {
    gameOver = true;
    document.getElementById("game-status").textContent = "💀 Game Over !";
    document.getElementById("replay-btn").style.display = "inline-block";
}

// Rejouer
document.getElementById("replay-btn").addEventListener("click", initGame);

// Lancer le jeu
initGame();
</script>
