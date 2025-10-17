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
 
    #game {
        background: #fff;
        padding: 20px 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        max-width: 600px;
        width: 100%;
    }
    #question {
        font-size: 20px;
        margin-bottom: 20px;
    }
    .option {
        display: block;
        background: #e0e0e0;
        padding: 10px 15px;
        margin-bottom: 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.2s;
        border: none;
        text-align: left;
    }
    .option:hover {
        background: #c0c0c0;
    }
    .correct {
        background-color: #4CAF50 !important; /* vert */
        color: #fff;
    }
    .wrong {
        background-color: #f44336 !important; /* rouge */
        color: #fff;
    }
    #nextBtn, #replayBtn {
        padding: 10px 20px;
        font-size: 16px;
        margin-top: 15px;
        cursor: pointer;
        border: none;
        border-radius: 5px;
        background-color: #4CAF50;
        color: #fff;
    }
    #score {
        font-weight: bold;
        margin-bottom: 10px;
    }
    #replayBtn {
        display: none;
        background-color: orange;
    }
</style>

<div id="game">
<center>
<h2>Questions de cours</h2>
</center>
    <div id="score">Score: 0</div>
    <div id="question"></div>
    <div id="options"></div>
    <button id="nextBtn">Suivant</button>
    <button id="replayBtn">Rejouer</button>
</div>

<script>
// Questions ENSPM Maroua
const questions = [
    // Questions Ingénierie des Arts Numériques
    {
        question: "Quel logiciel est principalement utilisé pour la modélisation 3D polygonale ?",
        options: ["Adobe Photoshop", "Blender", "Microsoft Excel", "WordPress"],
        answer: 1
    },
    {
        question: "En animation, que signifie l'acronyme 'IK' (Inverse Kinematics) ?",
        options: ["Une méthode de calcul des positions des articulations à partir de la position de l'extrémité.", "Un format de fichier image.", "Un type de lumière dans un moteur de rendu.", "Un algorithme de compression audio."],
        answer: 0
    },
    {
        question: "Lequel de ces langages est le plus couramment utilisé pour la programmation créative et le generative art ?",
        options: ["HTML", "Processing", "SQL", "CSS"],
        answer: 1
    },
    {
        question: "Quel terme décrit la création d'une prévisualisation approximative d'une animation ?",
        options: ["Rendering", "Sculpting", "Blocking", "Texturing"],
        answer: 2
    },
    {
        question: "Dans le pipeline d'un jeu vidéo, quelle étape suit la modélisation et précède l'animation ?",
        options: ["Le Rigging", "La Composition", "L'Éclairage", "La Programmation réseau"],
        answer: 0
    },
    {
        question: "Qu'est-ce que la 'Narratologie' ?",
        options: ["L'étude de la structure et des fonctionnements des récits.", "L'étude des nombres.", "L'étude des muscles.", "L'étude des réseaux informatiques."],
        answer: 0
    },
    {
        question: "Quel principe de design vise à guider le regard du spectateur vers l'élément le plus important d'une composition ?",
        options: ["La Règle des tiers", "Le Point de fuite", "La Hiérarchie visuelle", "L'Écrêtage des couleurs"],
        answer: 2
    },
    {
        question: "Quel type d'IA est souvent utilisé pour générer de nouvelles images à partir d'une base d'apprentissage ?",
        options: ["Réseaux de neurones convolutifs (CNN)", "Systèmes experts", "Réseaux bayésiens", "Algorithmes génétiques"],
        answer: 0
    },
    {
        question: "Quel format de fichier est préféré pour les textures avec canal alpha (transparence) ?",
        options: [".JPG", ".BMP", ".PNG", ".MP3"],
        answer: 2
    },
    {
        question: "Qu'est-ce qu'un 'Shader' ?",
        options: ["Un outil de sculpture digitale.", "Un programme qui calcule l'apparence d'une surface à l'écran.", "Un type de microphone.", "Un filtre photographique."],
        answer: 1
    },
    {
        question: "Le 'Storyboarding' est une étape cruciale pour :",
        options: ["Optimiser le code d'un jeu.", "Planifier et visualiser les séquences d'un film ou d'une animation.", "Sculpter un modèle 3D haute résolution.", "Configurer un serveur de base de données."],
        answer: 1
    },
    {
        question: "Quel moteur de jeu est connu pour son blueprint visual scripting system ?",
        options: ["Unity", "Unreal Engine", "Godot", "CryEngine"],
        answer: 1
    },
    {
        question: "En colorimétrie, que représente la 'Teinte' (Hue) ?",
        options: ["La luminosité de la couleur.", "La pureté ou l'intensité de la couleur.", "La couleur elle-même (rouge, bleu, vert...).", "La valeur en hexadécimal de la couleur."],
        answer: 2
    },
    {
        question: "Quel processus consiste à ajouter des détails de surface à un modèle 3D (ex : rugosité, métallicité) ?",
        options: ["L'Animation", "Le Texturing", "Le Rendering", "La Modélisation"],
        answer: 1
    },
    {
        question: "Le terme 'Low-Poly' se réfère à :",
        options: ["Un modèle 3D avec un nombre élevé de polygones.", "Un modèle 3D avec un nombre limité de polygones.", "Un algorithme de basse puissance.", "Un type de texture basse résolution."],
        answer: 1
    },
    {
        question: "Quel est l'objectif principal de l'étape de 'Rigging' ?",
        options: ["Créer la géométrie d'un personnage.", "Animer le personnage.", "Construire une armature et des contrôles pour animer un modèle.", "Applique les textures finales."],
        answer: 2
    },
    {
        question: "Le 'FPS' (Frames Per Second) dans une animation mesure :",
        options: ["La fréquence d'images.", "La taille du fichier.", "Le nombre de pixels.", "Le débit binaire."],
        answer: 0
    },
    {
        question: "Quel concept en game design décrit les règles qui régissent l'univers d'un jeu ?",
        options: ["Le Gameplay", "La Mécanique de jeu", "La Diegese", "Le HUD (Head-Up Display)"],
        answer: 1
    },
    {
        question: "En sound design, que signifie 'Foley' ?",
        options: ["La création en studio de bruitages synchronisés à l'image.", "Un type de compression audio.", "Un synthétiseur modulaire.", "Un format de fichier haute fidélité."],
        answer: 0
    },
    {
        question: "L'API 'OpenGL' est principalement utilisée pour :",
        options: ["Le réseautage en ligne.", "Le rendu graphique 2D et 3D.", "La manipulation de bases de données.", "Le traitement du signal audio."],
        answer: 1
    },
    // Questions Humanités Numériques
    {
        question: "Quel langage de balisage est la pierre angulaire du web et essentiel pour les projets de publication en HN ?",
        options: ["Python", "CSS", "HTML", "R"],
        answer: 2
    },
    {
        question: "Lequel de ces outils est un logiciel de référence pour l'analyse qualitative de données textuelles ?",
        options: ["NVivo", "Photoshop", "Unity", "Excel"],
        answer: 0
    },
    {
        question: "Qu'est-ce que la 'Stemmatique' ?",
        options: ["L'étude de la structure des plantes.", "L'analyse des réseaux sociaux.", "Une discipline qui reconstruit l'histoire textuelle d'une œuvre à partir de ses manuscrits.", "Une méthode de cryptographie."],
        answer: 2
    },
    {
        question: "Quel concept désigne des données si volumineuses qu'elles nécessitent des outils d'analyse spécifiques ?",
        options: ["Open Data", "Big Data", "Smart Data", "Meta Data"],
        answer: 1
    },
    {
        question: "Quel langage de programmation est particulièrement apprécié en HN pour l'analyse et la visualisation de données ?",
        options: ["Java", "C++", "R", "PHP"],
        answer: 2
    },
    {
        question: "Qu'est-ce qu'une 'Ontologie' dans le contexte des HN et du web sémantique ?",
        options: ["Une branche de la philosophie.", "Un modèle formel qui représente un domaine de connaissances par un ensemble de concepts et de relations.", "Un type de base de données non relationnelle.", "Un algorithme de machine learning."],
        answer: 1
    },
    {
        question: "La 'Fouille de Textes' (Text Mining) a principalement pour but :",
        options: ["De numériser des livres anciens.", "D'extraire automatiquement des informations et des connaissances à partir de textes.", "De corriger l'orthographe dans les documents.", "De traduire des textes dans une autre langue."],
        answer: 1
    },
    {
        question: "Le protocole IIIF (International Image Interoperability Framework) a pour objectiv :",
        options: ["De standardiser la diffusion des images numérisées du patrimoine.", "De sécuriser les transactions bancaires en ligne.", "De compresser les fichiers vidéo.", "De gérer les droits d'auteur automatiquement."],
        answer: 0
    },
    {
        question: "Quel outil est crucial pour la gestion de versions d'un code ou d'un ensemble de fichiers textuels ?",
        options: ["Git", "Google Drive", "Microsoft Word", "Adobe Acrobat"],
        answer: 0
    },
    {
        question: "La 'Visualisation de Données' (DataViz) permet :",
        options: ["De stocker plus efficacement les données.", "De représenter graphiquement des données pour en faciliter la compréhension.", "De nettoyer automatiquement les jeux de données.", "De crypter des informations sensibles."],
        answer: 1
    },
    {
        question: "Qu'est-ce que l'OCR (Optical Character Recognition) ?",
        options: ["Un type de scanner.", "Une technologie qui convertit les images de texte en texte éditable.", "Un format de fichier pour les images.", "Un algorithme de reconnaissance faciale."],
        answer: 1
    },
    {
        question: "Lequel de ces éléments est une métadonnée ?",
        options: ["Le corps du texte d'un roman.", "Le nom de l'auteur d'un fichier numérique.", "Les pixels d'une photographie.", "Le son d'un enregistrement audio."],
        answer: 1
    },
    {
        question: "Quelle méthode statistique est souvent utilisée pour analyser les réseaux sociaux ?",
        options: ["L'Analyse de Séquences", "L'Analyse de Réseaux (SNA)", "L'Analyse de Variance (ANOVA)", "La Régression Linéaire"],
        answer: 1
    },
    {
        question: "Le 'TEI' (Text Encoding Initiative) est :",
        options: ["Un moteur de recherche.", "Un langage de programmation.", "Un ensemble de guidelines pour encoder des textes en XML.", "Une base de données bibliographique."],
        answer: 2
    },
    {
        question: "La 'Cybersécurité' dans les HN concerne principalement :",
        options: ["La création de jeux vidéo.", "La protection des données de recherche et la vie privée des individus.", "L'optimisation pour les moteurs de recherche (SEO).", "La modélisation 3D d'objets historiques."],
        answer: 1
    },
    {
        question: "Qu'est-ce qu'un 'API' (Application Programming Interface) ?",
        options: ["Une interface utilisateur graphique.", "Un type de base de données.", "Une interface qui permet à des logiciels de communiquer entre eux.", "Un protocole de transfert de fichiers."],
        answer: 2
    },
    {
        question: "Le processus de 'Nettoyage de Données' (Data Cleaning) vise à :",
        options: ["Supprimer le plus de données possible.", "Repérer et corriger les erreurs et incohérences dans un jeu de données.", "Augmenter la taille d'un jeu de données.", "Convertir des données en un format image."],
        answer: 1
    },
    {
        question: "Un 'Système d'Information Géographique' (SIG) comme QGIS permet :",
        options: ["De créer des animations 3D.", "De gérer, analyser et visualiser des données spatialisées.", "De programmer des sites web dynamiques.", "D'analyser des séquences ADN."],
        answer: 1
    },
    {
        question: "Le 'Digital Humanities' est par nature :",
        options: ["Une discipline exclusivement littéraire.", "Une discipline exclusivement historique.", "Un champ de recherche interdisciplinaire.", "Un synonyme d'informatique."],
        answer: 2
    }
    
];

let currentQuestion = 0;
let score = 0;

// Afficher la question et options
function showQuestion() {
    const q = questions[currentQuestion];
    document.getElementById("question").innerText = q.question;
    const optionsDiv = document.getElementById("options");
    optionsDiv.innerHTML = "";

    q.options.forEach((opt, index) => {
        const btn = document.createElement("button");
        btn.classList.add("option");
        btn.innerText = opt;
        btn.addEventListener("click", () => selectAnswer(index, btn));
        optionsDiv.appendChild(btn);
    });
}

function selectAnswer(index, button) {
    const q = questions[currentQuestion];

    // Coloration des boutons
    const optionButtons = document.querySelectorAll(".option");
    optionButtons.forEach((btn, i) => {
        btn.disabled = true;
        if(i === q.answer){
            btn.classList.add("correct");
        } else if(i === index){
            btn.classList.add("wrong");
        }
    });

    // Incrémenter score si correct
    if(index === q.answer){
        score++;
        document.getElementById("score").innerText = "Score: " + score;
    }
}

// Passer à la question suivante
document.getElementById("nextBtn").addEventListener("click", () => {
    currentQuestion++;
    if(currentQuestion >= questions.length) {
        endGame();
    } else {
        showQuestion();
    }
});

function endGame() {
    document.getElementById("question").innerText = "Jeu terminé! Ton score final est: " + score;
    document.getElementById("options").innerHTML = "";
    document.getElementById("nextBtn").style.display = "none";
    document.getElementById("replayBtn").style.display = "block";
}

// Rejouer
document.getElementById("replayBtn").addEventListener("click", () => {
    currentQuestion = 0;
    score = 0;
    document.getElementById("score").innerText = "Score: 0";
    document.getElementById("nextBtn").style.display = "block";
    document.getElementById("replayBtn").style.display = "none";
    showQuestion();
});

// Lancer la première question
showQuestion();
</script>
