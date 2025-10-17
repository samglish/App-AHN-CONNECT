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

    .menu-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      max-width: 800px;
      width: 90%;
    }

    .menu-block {
      background: white;
      border-radius: 15px;
      padding: 30px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s, box-shadow 0.3s;
      cursor: pointer;
    }

    .menu-block:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .menu-block h2 {
      margin-bottom: 15px;
      color: #333;
    }

    .menu-block p {
      color: #666;
      font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 600px) {
      .menu-container {
        grid-template-columns: 1fr;
      }
    }
  </style>
  </br>
  <center>
 <div class="menu-container">
    
    <div class="menu-block" onclick="location.href='jeux1.php'">
      <h2>Questions de cours</h2>
      <p>Testez vos connaissances avec des questions issues des cours.</p></br>
     <i class="fas fa-user"></i> Abdoulatif Wirngo

    </div>

 <div class="menu-block" onclick="location.href='jeux3.php'">
  <h2>Géo Cameroun</h2>
  <p>Testez vos connaissances sur les 10 régions, les 58 départements et les 360 arrondissements et autres.</p></br>
  <i class="fas fa-user"></i> steve terence NLAM
</div>

 <div class="menu-block" onclick="location.href='jeux4.php'">
  <h2>Pays et Capitales</h2>
  <p>Testez vos connaissances géographiques à travers le monde ! Sélectionnez un continent et commencez à jouer.</p></br>
  <i class="fas fa-user"></i> steve terence NLAM
</div>

 <div class="menu-block" onclick="location.href='jeux2.php'">
      <h2>Jeu de blocs</h2>
      <p>Amusez-vous avec un mini jeu de blocs qui tombent façon Tetris simplifié.</p></br>
     <i class="fas fa-user"></i> Abdoulatif Wirngo
    </div>
    
  </div>
  </center>
