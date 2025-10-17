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
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
    
        
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 800px;
            padding: 30px;
            text-align: center;
        }
        
        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .header img {
            width: 80px;
            margin-right: 15px;
        }
        
  
        
        .description {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 1.1rem;
            line-height: 1.5;
        }
        
        .quiz-type-selector {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        .quiz-btn {
            padding: 12px 20px;
            background-color: #007a5e;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .quiz-btn:hover {
            background-color: #005640;
            transform: translateY(-2px);
        }
        
        .quiz-btn.active {
            background-color: #ce1126;
        }
        
        .question-container {
            margin: 25px 0;
        }
        
        .question {
            font-size: 1.4rem;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 25px;
        }
        
        .option {
            padding: 15px;
            background-color: #ecf0f1;
            border: 2px solid #bdc3c7;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 1.1rem;
        }
        
        .option:hover {
            background-color: #d6dbdf;
            transform: scale(1.02);
        }
        
        .option.correct {
            background-color: #2ecc71;
            color: white;
            border-color: #27ae60;
        }
        
        .option.incorrect {
            background-color: #e74c3c;
            color: white;
            border-color: #c0392b;
        }
        
        .controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        
        .control-btn {
            padding: 12px 25px;
            background-color: #ce1126;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .control-btn:hover {
            background-color: #a50e20;
            transform: translateY(-2px);
        }
        
        .score-container {
            margin-top: 20px;
            font-size: 1.3rem;
            color: #2c3e50;
            font-weight: bold;
        }
        
        .progress {
            margin-top: 20px;
            font-size: 1.1rem;
            color: #7f8c8d;
        }
        
        .flag {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        
        .hidden {
            display: none;
        }
        
        @media (max-width: 600px) {
            .options {
                grid-template-columns: 1fr;
            }
            
            .quiz-type-selector {
                flex-direction: column;
                align-items: center;
            }
            
            .quiz-btn {
                width: 100%;
            }
            
            .controls {
                flex-direction: column;
            }
        }
    </style>


        <div class="header">
            <div class="flag">🇨🇲</div>
        </div>
        <h1>Quiz Géographique Complet du Cameroun</h1>
        <p class="description">Testez vos connaissances sur les 10 régions, 58 départements et 360 arrondissements du Cameroun</p>
        
        <div class="quiz-type-selector">
            <button class="quiz-btn active" data-type="regions">Régions & Chefs-lieux</button>
            <button class="quiz-btn" data-type="departements">Départements</button>
            <button class="quiz-btn" data-type="arrondissements">Arrondissements</button>
            <button class="quiz-btn" data-type="mixte">Quiz Mixte</button>
        </div>
        
        <div class="question-container">
            <p class="question" id="question">Chargement de la question...</p>
            <div class="options" id="options">
                <div class="option">Option 1</div>
                <div class="option">Option 2</div>
                <div class="option">Option 3</div>
                <div class="option">Option 4</div>
            </div>
        </div>
        
        <div class="controls">
            <button class="control-btn" id="next-btn">Question Suivante</button>
            <button class="control-btn" id="restart-btn">Nouvelle Partie</button>
        </div>
        
        <div class="score-container" id="score-container">
            Score: <span id="score">0</span> | Questions: <span id="total">0</span>
        </div>
        
        <div class="progress" id="progress">
            Mode: <span id="mode">Régions</span>
        </div>
 

    <script>
        // Base de données complète sur le Cameroun
        const camerounData = {
            regions: [
                { nom: "Adamaoua", chefLieu: "Ngaoundéré" },
                { nom: "Centre", chefLieu: "Yaoundé" },
                { nom: "Est", chefLieu: "Bertoua" },
                { nom: "Extrême-Nord", chefLieu: "Maroua" },
                { nom: "Littoral", chefLieu: "Douala" },
                { nom: "Nord", chefLieu: "Garoua" },
                { nom: "Ouest", chefLieu: "Bafoussam" },
                { nom: "Sud", chefLieu: "Ebolowa" },
                { nom: "Sud-Ouest", chefLieu: "Buéa" },
                { nom: "Nord-Ouest", chefLieu: "Bamenda" }
            ],
            departements: [
                // Départements de l'Adamaoua
                { nom: "Djérem", chefLieu: "Tibati", region: "Adamaoua" },
                { nom: "Faro-et-Déo", chefLieu: "Tignère", region: "Adamaoua" },
                { nom: "Mayo-Banyo", chefLieu: "Banyo", region: "Adamaoua" },
                { nom: "Mbéré", chefLieu: "Meiganga", region: "Adamaoua" },
                { nom: "Vina", chefLieu: "Ngaoundéré", region: "Adamaoua" },
                
                // Départements du Centre
                { nom: "Haute-Sanaga", chefLieu: "Nanga-Eboko", region: "Centre" },
                { nom: "Lekié", chefLieu: "Monatélé", region: "Centre" },
                { nom: "Mbam-et-Inoubou", chefLieu: "Bafia", region: "Centre" },
                { nom: "Mbam-et-Kim", chefLieu: "Ntui", region: "Centre" },
                { nom: "Méfou-et-Afamba", chefLieu: "Mfou", region: "Centre" },
                { nom: "Méfou-et-Akono", chefLieu: "Ngoumou", region: "Centre" },
                { nom: "Mfoundi", chefLieu: "Yaoundé", region: "Centre" },
                { nom: "Nyong-et-Kéllé", chefLieu: "Éséka", region: "Centre" },
                { nom: "Nyong-et-Mfoumou", chefLieu: "Akonolinga", region: "Centre" },
                { nom: "Nyong-et-So'o", chefLieu: "Mbalmayo", region: "Centre" },
                
                // Départements de l'Est
                { nom: "Boumba-et-Ngoko", chefLieu: "Yokadouma", region: "Est" },
                { nom: "Haut-Nyong", chefLieu: "Abong-Mbang", region: "Est" },
                { nom: "Kadey", chefLieu: "Batouri", region: "Est" },
                { nom: "Lom-et-Djérem", chefLieu: "Bertoua", region: "Est" },
                
                // Départements de l'Extrême-Nord
                { nom: "Diamaré", chefLieu: "Maroua", region: "Extrême-Nord" },
                { nom: "Logone-et-Chari", chefLieu: "Kousséri", region: "Extrême-Nord" },
                { nom: "Mayo-Danay", chefLieu: "Yagoua", region: "Extrême-Nord" },
                { nom: "Mayo-Kani", chefLieu: "Kaélé", region: "Extrême-Nord" },
                { nom: "Mayo-Sava", chefLieu: "Mora", region: "Extrême-Nord" },
                { nom: "Mayo-Tsanaga", chefLieu: "Mokolo", region: "Extrême-Nord" },
                
                // Départements du Littoral
                { nom: "Moungo", chefLieu: "Nkongsamba", region: "Littoral" },
                { nom: "Nkam", chefLieu: "Yabassi", region: "Littoral" },
                { nom: "Sanaga-Maritime", chefLieu: "Édéa", region: "Littoral" },
                { nom: "Wouri", chefLieu: "Douala", region: "Littoral" },
                
                // Départements du Nord
                { nom: "Bénoué", chefLieu: "Garoua", region: "Nord" },
                { nom: "Faro", chefLieu: "Poli", region: "Nord" },
                { nom: "Mayo-Louti", chefLieu: "Guider", region: "Nord" },
                { nom: "Mayo-Rey", chefLieu: "Tcholliré", region: "Nord" },
                
                // Départements de l'Ouest
                { nom: "Bamboutos", chefLieu: "Mbouda", region: "Ouest" },
                { nom: "Haut-Nkam", chefLieu: "Bafang", region: "Ouest" },
                { nom: "Hauts-Plateaux", chefLieu: "Baham", region: "Ouest" },
                { nom: "Koung-Khi", chefLieu: "Bandjoun", region: "Ouest" },
                { nom: "Menoua", chefLieu: "Dschang", region: "Ouest" },
                { nom: "Mifi", chefLieu: "Bafoussam", region: "Ouest" },
                { nom: "Ndé", chefLieu: "Bangangté", region: "Ouest" },
                { nom: "Noun", chefLieu: "Foumban", region: "Ouest" },
                
                // Départements du Sud
                { nom: "Dja-et-Lobo", chefLieu: "Sangmélima", region: "Sud" },
                { nom: "Mvila", chefLieu: "Ebolowa", region: "Sud" },
                { nom: "Océan", chefLieu: "Kribi", region: "Sud" },
                { nom: "Vallée-du-Ntem", chefLieu: "Ambam", region: "Sud" },
                
                // Départements du Sud-Ouest
                { nom: "Fako", chefLieu: "Limbé", region: "Sud-Ouest" },
                { nom: "Koupé-Manengouba", chefLieu: "Bangem", region: "Sud-Ouest" },
                { nom: "Lebialem", chefLieu: "Menji", region: "Sud-Ouest" },
                { nom: "Manyu", chefLieu: "Mamfé", region: "Sud-Ouest" },
                { nom: "Meme", chefLieu: "Kumba", region: "Sud-Ouest" },
                { nom: "Ndian", chefLieu: "Mundemba", region: "Sud-Ouest" },
                
                // Départements du Nord-Ouest
                { nom: "Boyo", chefLieu: "Fundong", region: "Nord-Ouest" },
                { nom: "Bui", chefLieu: "Kumbo", region: "Nord-Ouest" },
                { nom: "Donga-Mantung", chefLieu: "Nkambé", region: "Nord-Ouest" },
                { nom: "Menchum", chefLieu: "Wum", region: "Nord-Ouest" },
                { nom: "Mezam", chefLieu: "Bamenda", region: "Nord-Ouest" },
                { nom: "Momo", chefLieu: "Mbengwi", region: "Nord-Ouest" },
                { nom: "Ngo-Ketunjia", chefLieu: "Ndop", region: "Nord-Ouest" }
            ],
            arrondissements: [
                // Exemples d'arrondissements (liste abrégée pour la démo)
                { nom: "Yaoundé I", departement: "Mfoundi", region: "Centre" },
                { nom: "Yaoundé II", departement: "Mfoundi", region: "Centre" },
                { nom: "Yaoundé III", departement: "Mfoundi", region: "Centre" },
                { nom: "Yaoundé IV", departement: "Mfoundi", region: "Centre" },
                { nom: "Yaoundé V", departement: "Mfoundi", region: "Centre" },
                { nom: "Yaoundé VI", departement: "Mfoundi", region: "Centre" },
                { nom: "Yaoundé VII", departement: "Mfoundi", region: "Centre" },
                { nom: "Douala I", departement: "Wouri", region: "Littoral" },
                { nom: "Douala II", departement: "Wouri", region: "Littoral" },
                { nom: "Douala III", departement: "Wouri", region: "Littoral" },
                { nom: "Douala IV", departement: "Wouri", region: "Littoral" },
                { nom: "Douala V", departement: "Wouri", region: "Littoral" },
                { nom: "Garoua I", departement: "Bénoué", region: "Nord" },
                { nom: "Garoua II", departement: "Bénoué", region: "Nord" },
                { nom: "Garoua III", departement: "Bénoué", region: "Nord" },
                { nom: "Maroua I", departement: "Diamaré", region: "Extrême-Nord" },
                { nom: "Maroua II", departement: "Diamaré", region: "Extrême-Nord" },
                { nom: "Maroua III", departement: "Diamaré", region: "Extrême-Nord" },
                { nom: "Bafoussam I", departement: "Mifi", region: "Ouest" },
                { nom: "Bafoussam II", departement: "Mifi", region: "Ouest" },
                { nom: "Bafoussam III", departement: "Mifi", region: "Ouest" },
                { nom: "Bamenda I", departement: "Mezam", region: "Nord-Ouest" },
                { nom: "Bamenda II", departement: "Mezam", region: "Nord-Ouest" },
                { nom: "Bamenda III", departement: "Mezam", region: "Nord-Ouest" },
                // ... (on pourrait ajouter les 360 arrondissements)
            ]
        };

        // Éléments DOM
        const questionElement = document.getElementById('question');
        const optionsElement = document.getElementById('options');
        const scoreElement = document.getElementById('score');
        const totalElement = document.getElementById('total');
        const modeElement = document.getElementById('mode');
        const nextButton = document.getElementById('next-btn');
        const restartButton = document.getElementById('restart-btn');
        const quizButtons = document.querySelectorAll('.quiz-btn');
        
        // Variables du jeu
        let currentQuizType = 'regions';
        let score = 0;
        let totalQuestions = 0;
        
        // Initialisation du quiz
        function initQuiz() {
            score = 0;
            totalQuestions = 0;
            updateScore();
            generateQuestion();
            nextButton.disabled = true;
        }
        
        // Générer une question aléatoire
        function generateQuestion() {
            let questionData;
            let correctAnswer;
            let options = [];
            
            // Sélectionner aléatoirement le type de question pour le mode mixte
            let questionType = currentQuizType;
            if (currentQuizType === 'mixte') {
                const types = ['regions', 'departements', 'arrondissements'];
                questionType = types[Math.floor(Math.random() * types.length)];
            }
            
            // Générer une question selon le type
            switch(questionType) {
                case 'regions':
                    const randomRegion = camerounData.regions[Math.floor(Math.random() * camerounData.regions.length)];
                    
                    if (Math.random() > 0.5) {
                        // Question: "Quel est le chef-lieu de [région] ?"
                        questionData = `Quel est le chef-lieu de la région ${randomRegion.nom} ?`;
                        correctAnswer = randomRegion.chefLieu;
                        
                        // Générer des options
                        options = [correctAnswer];
                        while (options.length < 4) {
                            const randomOption = camerounData.regions[Math.floor(Math.random() * camerounData.regions.length)].chefLieu;
                            if (!options.includes(randomOption)) {
                                options.push(randomOption);
                            }
                        }
                    } else {
                        // Question: "Dans quelle région se trouve [chef-lieu] ?"
                        questionData = `Dans quelle région se trouve la ville de ${randomRegion.chefLieu} ?`;
                        correctAnswer = randomRegion.nom;
                        
                        // Générer des options
                        options = [correctAnswer];
                        while (options.length < 4) {
                            const randomOption = camerounData.regions[Math.floor(Math.random() * camerounData.regions.length)].nom;
                            if (!options.includes(randomOption)) {
                                options.push(randomOption);
                            }
                        }
                    }
                    break;
                    
                case 'departements':
                    const randomDept = camerounData.departements[Math.floor(Math.random() * camerounData.departements.length)];
                    
                    if (Math.random() > 0.5) {
                        // Question: "Quel est le chef-lieu du département [département] ?"
                        questionData = `Quel est le chef-lieu du département ${randomDept.nom} ?`;
                        correctAnswer = randomDept.chefLieu;
                        
                        // Générer des options
                        options = [correctAnswer];
                        while (options.length < 4) {
                            const randomOption = camerounData.departements[Math.floor(Math.random() * camerounData.departements.length)].chefLieu;
                            if (!options.includes(randomOption)) {
                                options.push(randomOption);
                            }
                        }
                    } else {
                        // Question: "Dans quel département se trouve [chef-lieu] ?"
                        questionData = `Dans quel département se trouve la ville de ${randomDept.chefLieu} ?`;
                        correctAnswer = randomDept.nom;
                        
                        // Générer des options
                        options = [correctAnswer];
                        while (options.length < 4) {
                            const randomOption = camerounData.departements[Math.floor(Math.random() * camerounData.departements.length)].nom;
                            if (!options.includes(randomOption)) {
                                options.push(randomOption);
                            }
                        }
                    }
                    break;
                    
                case 'arrondissements':
                    const randomArrond = camerounData.arrondissements[Math.floor(Math.random() * camerounData.arrondissements.length)];
                    
                    if (Math.random() > 0.5) {
                        // Question: "Dans quel département se trouve l'arrondissement [arrondissement] ?"
                        questionData = `Dans quel département se trouve l'arrondissement de ${randomArrond.nom} ?`;
                        correctAnswer = randomArrond.departement;
                        
                        // Générer des options
                        options = [correctAnswer];
                        while (options.length < 4) {
                            const randomOption = camerounData.departements[Math.floor(Math.random() * camerounData.departements.length)].nom;
                            if (!options.includes(randomOption)) {
                                options.push(randomOption);
                            }
                        }
                    } else {
                        // Question: "Dans quelle région se trouve l'arrondissement [arrondissement] ?"
                        questionData = `Dans quelle région se trouve l'arrondissement de ${randomArrond.nom} ?`;
                        correctAnswer = randomArrond.region;
                        
                        // Générer des options
                        options = [correctAnswer];
                        while (options.length < 4) {
                            const randomOption = camerounData.regions[Math.floor(Math.random() * camerounData.regions.length)].nom;
                            if (!options.includes(randomOption)) {
                                options.push(randomOption);
                            }
                        }
                    }
                    break;
            }
            
            // Mélanger les options
            options = shuffleArray(options);
            
            // Afficher la question
            questionElement.textContent = questionData;
            
            // Réinitialiser les options
            optionsElement.innerHTML = '';
            
            // Ajouter les options
            options.forEach((option, index) => {
                const optionElement = document.createElement('div');
                optionElement.classList.add('option');
                optionElement.textContent = option;
                optionElement.dataset.value = option;
                optionElement.addEventListener('click', () => checkAnswer(option, correctAnswer));
                optionsElement.appendChild(optionElement);
            });
            
            // Mettre à jour le compteur
            totalQuestions++;
            updateScore();
            updateMode();
        }
        
        // Mélanger un tableau (aléatoire)
        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }
        
        // Vérifier la réponse
        function checkAnswer(selectedAnswer, correctAnswer) {
            const options = optionsElement.querySelectorAll('.option');
            
            // Désactiver les options après sélection
            options.forEach(option => {
                option.style.pointerEvents = 'none';
            });
            
            // Marquer la bonne et la mauvaise réponse
            options.forEach(option => {
                if (option.dataset.value === correctAnswer) {
                    option.classList.add('correct');
                }
                if (option.dataset.value === selectedAnswer && selectedAnswer !== correctAnswer) {
                    option.classList.add('incorrect');
                }
            });
            
            // Mettre à jour le score si correct
            if (selectedAnswer === correctAnswer) {
                score++;
                updateScore();
            }
            
            // Activer le bouton suivant
            nextButton.disabled = false;
        }
        
        // Mettre à jour le score
        function updateScore() {
            scoreElement.textContent = score;
            totalElement.textContent = totalQuestions;
        }
        
        // Mettre à jour l'affichage du mode
        function updateMode() {
            let modeText;
            switch(currentQuizType) {
                case 'regions': modeText = "Régions & Chefs-lieux"; break;
                case 'departements': modeText = "Départements"; break;
                case 'arrondissements': modeText = "Arrondissements"; break;
                case 'mixte': modeText = "Quiz Mixte"; break;
            }
            modeElement.textContent = modeText;
        }
        
        // Changer le type de quiz
        quizButtons.forEach(button => {
            button.addEventListener('click', () => {
                quizButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                currentQuizType = button.dataset.type;
                initQuiz();
            });
        });
        
        // Bouton question suivante
        nextButton.addEventListener('click', () => {
            generateQuestion();
            nextButton.disabled = true;
        });
        
        // Bouton recommencer
        restartButton.addEventListener('click', initQuiz);
        
        // Initialiser le quiz au chargement
        window.addEventListener('load', initQuiz);
    </script>
