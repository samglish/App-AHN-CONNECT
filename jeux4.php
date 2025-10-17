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
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            width: 90%;
            max-width: 800px;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .world-icon {
            font-size: 4rem;
            color: #3498db;
            margin-bottom: 20px;
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
    
        
        .description {
            color: #34495e;
            margin-bottom: 30px;
            font-size: 1.2rem;
            line-height: 1.6;
        }
        
        .continent-selector {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 30px;
        }
        
        .continent-btn {
            padding: 12px 20px;
            background: linear-gradient(to right, #3498db, #2980b9);
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .continent-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .continent-btn.active {
            background: linear-gradient(to right, #e74c3c, #c0392b);
        }
        
        .game-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            background: linear-gradient(to right, #f1c40f, #f39c12);
            padding: 15px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
        }
        
        .question-container {
            margin: 30px 0;
        }
        
        .question {
            font-size: 1.6rem;
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: 600;
            line-height: 1.4;
        }
        
        .options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .option {
            padding: 18px;
            background-color: #ecf0f1;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 1.1rem;
            font-weight: 500;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .option:hover {
            background-color: #d6dbdf;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .option.correct {
            background: linear-gradient(to right, #2ecc71, #27ae60);
            color: white;
        }
        
        .option.incorrect {
            background: linear-gradient(to right, #e74c3c, #c0392b);
            color: white;
        }
        
        .controls {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        
        .control-btn {
            padding: 15px 30px;
            background: linear-gradient(to right, #2c3e50, #1a252f);
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .control-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .control-btn.next {
            background: linear-gradient(to right, #3498db, #2980b9);
        }
        
        .score-container {
            margin-top: 25px;
            font-size: 1.4rem;
            color: #2c3e50;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        
        .progress-bar {
            height: 10px;
            background-color: #ecf0f1;
            border-radius: 5px;
            margin: 20px 0;
            overflow: hidden;
        }
        
        .progress {
            height: 100%;
            background: linear-gradient(to right, #2ecc71, #27ae60);
            border-radius: 5px;
            width: 0%;
            transition: width 0.5s ease;
        }
        
        .timer {
            font-size: 1.2rem;
            color: #e74c3c;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .result-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        
        .modal-content h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }
        
        .modal-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        .trophy {
            font-size: 4rem;
            color: #f1c40f;
            margin-bottom: 20px;
        }
        
        .difficulty-selector {
            margin: 20px 0;
        }
        
        .difficulty-btn {
            padding: 10px 20px;
            margin: 0 5px;
            background-color: #bdc3c7;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
        }
        
        .difficulty-btn.active {
            background-color: #2c3e50;
        }
        
        @media (max-width: 768px) {
            .options {
                grid-template-columns: 1fr;
            }
            
            .continent-selector {
                flex-direction: column;
                align-items: center;
            }
            
            .continent-btn {
                width: 100%;
            }
            
            .controls {
                flex-direction: column;
            }
            
            .question {
                font-size: 1.3rem;
            }
            
            .container {
                padding: 25px;
            }
        }
        
        /* Animation pour les nouvelles questions */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
    </style>
 
        <div class="world-icon">
            <i class="fas fa-globe-americas"></i>
        </div>
        <h1>Quiz Mondial des Pays et Capitales</h1>
        <h4>conçu par steve terence NLAM</h4>
        <p class="description">Testez vos connaissances géographiques à travers le monde ! Sélectionnez un continent et commencez à jouer.</p>
        
        <div class="continent-selector">
            <button class="continent-btn active" data-continent="monde">
                <i class="fas fa-globe"></i> Monde
            </button>
            <button class="continent-btn" data-continent="europe">
                <i class="fas fa-monument"></i> Europe
            </button>
            <button class="continent-btn" data-continent="afrique">
                <i class="fas fa-globe-africa"></i> Afrique
            </button>
            <button class="continent-btn" data-continent="asie">
                <i class="fas fa-archway"></i> Asie
            </button>
            <button class="continent-btn" data-continent="amerique">
                <i class="fas fa-mountain"></i> Amérique
            </button>
            <button class="continent-btn" data-continent="oceanie">
                <i class="fas fa-umbrella-beach"></i> Océanie
            </button>
        </div>
        
        <div class="difficulty-selector">
            <button class="difficulty-btn active" data-difficulty="normal">Normal</button>
            <button class="difficulty-btn" data-difficulty="difficile">Difficile</button>
            <button class="difficulty-btn" data-difficulty="expert">Expert</button>
        </div>
        
        <div class="game-info">
            <div class="score-container">
                <i class="fas fa-star"></i>
                Score: <span id="score">0</span>
            </div>
            <div class="timer">
                <i class="fas fa-clock"></i> <span id="time">30</span>s
            </div>
        </div>
        
        <div class="progress-bar">
            <div class="progress" id="progress"></div>
        </div>
        
        <div class="question-container">
            <p class="question" id="question">Quelle est la capitale de la France ?</p>
            <div class="options" id="options">
                <div class="option">Londres</div>
                <div class="option">Berlin</div>
                <div class="option">Paris</div>
                <div class="option">Madrid</div>
            </div>
        </div>
        
        <div class="controls">
            <button class="control-btn next" id="next-btn">
                <i class="fas fa-arrow-right"></i> Question Suivante
            </button>
            <button class="control-btn" id="restart-btn">
                <i class="fas fa-redo"></i> Recommencer
            </button>
        </div>
    </div>

    <div class="result-modal" id="result-modal">
        <div class="modal-content">
            <div class="trophy">
                <i class="fas fa-trophy"></i>
            </div>
            <h2>Quiz Terminé !</h2>
            <p>Votre score final: <span id="final-score">0</span></p>
            <button class="control-btn" id="close-modal">
                <i class="fas fa-play"></i> Rejouer
            </button>
        </div>
  

    <script>
        // Base de données des pays et capitales par continent
        const countriesData = {
            monde: [
                { country: "France", capital: "Paris" },
                { country: "Allemagne", capital: "Berlin" },
                { country: "Italie", capital: "Rome" },
                { country: "Espagne", capital: "Madrid" },
                { country: "Royaume-Uni", capital: "Londres" },
                { country: "États-Unis", capital: "Washington" },
                { country: "Canada", capital: "Ottawa" },
                { country: "Japon", capital: "Tokyo" },
                { country: "Chine", capital: "Pékin" },
                { country: "Inde", capital: "New Delhi" },
                { country: "Brésil", capital: "Brasilia" },
                { country: "Australie", capital: "Canberra" },
                { country: "Russie", capital: "Moscou" },
                { country: "Egypte", capital: "Le Caire" },
                { country: "Afrique du Sud", capital: "Pretoria" },
                { country: "Mexique", capital: "Mexico" },
                { country: "Argentine", capital: "Buenos Aires" },
                { country: "Suède", capital: "Stockholm" },
                { country: "Norvège", capital: "Oslo" },
                { country: "Turquie", capital: "Ankara" }
            ],
            europe: [
                { country: "France", capital: "Paris" },
                { country: "Allemagne", capital: "Berlin" },
                { country: "Italie", capital: "Rome" },
                { country: "Espagne", capital: "Madrid" },
                { country: "Royaume-Uni", capital: "Londres" },
                { country: "Portugal", capital: "Lisbonne" },
                { country: "Suisse", capital: "Berne" },
                { country: "Belgique", capital: "Bruxelles" },
                { country: "Pays-Bas", capital: "Amsterdam" },
                { country: "Suède", capital: "Stockholm" },
                { country: "Norvège", capital: "Oslo" },
                { country: "Danemark", capital: "Copenhague" },
                { country: "Finlande", capital: "Helsinki" },
                { country: "Pologne", capital: "Varsovie" },
                { country: "Grèce", capital: "Athènes" }
            ],
            afrique: [
                { country: "Algérie", capital: "Alger" },
                { country: "Maroc", capital: "Rabat" },
                { country: "Tunisie", capital: "Tunis" },
                { country: "Égypte", capital: "Le Caire" },
                { country: "Sénégal", capital: "Dakar" },
                { country: "Côte d'Ivoire", capital: "Yamoussoukro" },
                { country: "Nigeria", capital: "Abuja" },
                { country: "Kenya", capital: "Nairobi" },
                { country: "Afrique du Sud", capital: "Pretoria" },
                { country: "Ghana", capital: "Accra" },
                { country: "Cameroun", capital: "Yaoundé" },
                { country: "Mali", capital: "Bamako" },
                { country: "Ethiopie", capital: "Addis-Abeba" },
                { country: "Tanzanie", capital: "Dodoma" },
                { country: "Angola", capital: "Luanda" }
            ],
            asie: [
                { country: "Chine", capital: "Pékin" },
                { country: "Japon", capital: "Tokyo" },
                { country: "Inde", capital: "New Delhi" },
                { country: "Corée du Sud", capital: "Séoul" },
                { country: "Indonésie", capital: "Jakarta" },
                { country: "Thaïlande", capital: "Bangkok" },
                { country: "Vietnam", capital: "Hanoï" },
                { country: "Malaisie", capital: "Kuala Lumpur" },
                { country: "Philippines", capital: "Manille" },
                { country: "Arabie Saoudite", capital: "Riyad" },
                { country: "Iran", capital: "Téhéran" },
                { country: "Turquie", capital: "Ankara" },
                { country: "Israël", capital: "Jérusalem" },
                { country: "Pakistan", capital: "Islamabad" },
                { country: "Bangladesh", capital: "Dacca" }
            ],
            amerique: [
                { country: "États-Unis", capital: "Washington" },
                { country: "Canada", capital: "Ottawa" },
                { country: "Mexique", capital: "Mexico" },
                { country: "Brésil", capital: "Brasilia" },
                { country: "Argentine", capital: "Buenos Aires" },
                { country: "Chili", capital: "Santiago" },
                { country: "Pérou", capital: "Lima" },
                { country: "Colombie", capital: "Bogota" },
                { country: "Venezuela", capital: "Caracas" },
                { country: "Cuba", capital: "La Havane" },
                { country: "Jamaïque", capital: "Kingston" },
                { country: "Équateur", capital: "Quito" },
                { country: "Bolivie", capital: "La Paz" },
                { country: "Uruguay", capital: "Montevideo" },
                { country: "Paraguay", capital: "Asuncion" }
            ],
            oceanie: [
                { country: "Australie", capital: "Canberra" },
                { country: "Nouvelle-Zélande", capital: "Wellington" },
                { country: "Papouasie-Nouvelle-Guinée", capital: "Port Moresby" },
                { country: "Fidji", capital: "Suva" },
                { country: "Îles Salomon", capital: "Honiara" },
                { country: "Vanuatu", capital: "Port-Vila" },
                { country: "Samoa", capital: "Apia" },
                { country: "Tonga", capital: "Nuku'alofa" },
                { country: "Kiribati", capital: "Tarawa" },
                { country: "Micronésie", capital: "Palikir" },
                { country: "Palaos", capital: "Ngerulmud" },
                { country: "Marshall", capital: "Majuro" },
                { country: "Tuvalu", capital: "Funafuti" },
                { country: "Nauru", capital: "Yaren" }
            ]
        };

        // Éléments DOM
        const questionElement = document.getElementById('question');
        const optionsElement = document.getElementById('options');
        const scoreElement = document.getElementById('score');
        const timeElement = document.getElementById('time');
        const progressElement = document.getElementById('progress');
        const nextButton = document.getElementById('next-btn');
        const restartButton = document.getElementById('restart-btn');
        const continentButtons = document.querySelectorAll('.continent-btn');
        const difficultyButtons = document.querySelectorAll('.difficulty-btn');
        const resultModal = document.getElementById('result-modal');
        const finalScoreElement = document.getElementById('final-score');
        const closeModalButton = document.getElementById('close-modal');
        
        // Variables du jeu
        let currentContinent = 'monde';
        let currentDifficulty = 'normal';
        let score = 0;
        let timer;
        let timeLeft = 30;
        let currentQuestion = {};
        let acceptingAnswers = false;
        
        // Initialisation du quiz
        function initQuiz() {
            score = 0;
            scoreElement.textContent = score;
            clearInterval(timer);
            timeLeft = 30;
            timeElement.textContent = timeLeft;
            progressElement.style.width = '0%';
            
            generateQuestion();
            startTimer();
        }
        
        // Générer une question aléatoire
        function generateQuestion() {
            acceptingAnswers = true;
            
            // Obtenir les pays du continent sélectionné
            const continentCountries = countriesData[currentContinent];
            
            // Sélectionner un pays aléatoire
            const randomIndex = Math.floor(Math.random() * continentCountries.length);
            currentQuestion = continentCountries[randomIndex];
            
            // Générer la question (pays -> capitale ou capitale -> pays)
            let questionType = Math.random() > 0.5 ? 'country' : 'capital';
            
            if (questionType === 'country') {
                questionElement.textContent = `Quelle est la capitale du ${currentQuestion.country} ?`;
            } else {
                questionElement.textContent = `De quel pays ${currentQuestion.capital} est-elle la capitale ?`;
            }
            
            // Générer des options aléatoires
            let options = [];
            
            if (questionType === 'country') {
                options.push(currentQuestion.capital);
                
                // Ajouter 3 autres capitales aléatoires
                while (options.length < 4) {
                    const randomOption = continentCountries[Math.floor(Math.random() * continentCountries.length)].capital;
                    if (!options.includes(randomOption)) {
                        options.push(randomOption);
                    }
                }
            } else {
                options.push(currentQuestion.country);
                
                // Ajouter 3 autres pays aléatoires
                while (options.length < 4) {
                    const randomOption = continentCountries[Math.floor(Math.random() * continentCountries.length)].country;
                    if (!options.includes(randomOption)) {
                        options.push(randomOption);
                    }
                }
            }
            
            // Mélanger les options
            options = shuffleArray(options);
            
            // Afficher les options
            optionsElement.innerHTML = '';
            options.forEach(option => {
                const optionElement = document.createElement('div');
                optionElement.classList.add('option');
                optionElement.textContent = option;
                
                optionElement.addEventListener('click', e => {
                    if (!acceptingAnswers) return;
                    
                    acceptingAnswers = false;
                    const selectedOption = e.target.textContent;
                    
                    // Vérifier la réponse
                    let correctAnswer = questionType === 'country' ? currentQuestion.capital : currentQuestion.country;
                    
                    if (selectedOption === correctAnswer) {
                        e.target.classList.add('correct');
                        score += currentDifficulty === 'normal' ? 10 : currentDifficulty === 'difficile' ? 15 : 20;
                        scoreElement.textContent = score;
                    } else {
                        e.target.classList.add('incorrect');
                        // Trouver et marquer la bonne réponse
                        optionsElement.querySelectorAll('.option').forEach(opt => {
                            if (opt.textContent === correctAnswer) {
                                opt.classList.add('correct');
                            }
                        });
                    }
                    
                    // Arrêter le timer
                    clearInterval(timer);
                });
                
                optionsElement.appendChild(optionElement);
            });
            
            // Animation d'apparition
            questionElement.classList.remove('fade-in');
            optionsElement.classList.remove('fade-in');
            void questionElement.offsetWidth;
            void optionsElement.offsetWidth;
            questionElement.classList.add('fade-in');
            optionsElement.classList.add('fade-in');
        }
        
        // Mélanger un tableau (aléatoire)
        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }
        
        // Démarrer le timer
        function startTimer() {
            clearInterval(timer);
            timeLeft = 30;
            timeElement.textContent = timeLeft;
            
            timer = setInterval(() => {
                timeLeft--;
                timeElement.textContent = timeLeft;
                progressElement.style.width = `${((30 - timeLeft) / 30) * 100}%`;
                
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    acceptingAnswers = false;
                    
                    // Marquer la bonne réponse
                    const options = optionsElement.querySelectorAll('.option');
                    const questionType = questionElement.textContent.includes('capitale') ? 'country' : 'capital';
                    const correctAnswer = questionType === 'country' ? currentQuestion.capital : currentQuestion.country;
                    
                    options.forEach(option => {
                        if (option.textContent === correctAnswer) {
                            option.classList.add('correct');
                        }
                    });
                }
            }, 1000);
        }
        
        // Changer de continent
        continentButtons.forEach(button => {
            button.addEventListener('click', () => {
                continentButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                currentContinent = button.dataset.continent;
                initQuiz();
            });
        });
        
        // Changer de difficulté
        difficultyButtons.forEach(button => {
            button.addEventListener('click', () => {
                difficultyButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                currentDifficulty = button.dataset.difficulty;
                initQuiz();
            });
        });
        
        // Bouton question suivante
        nextButton.addEventListener('click', () => {
            generateQuestion();
            startTimer();
        });
        
        // Bouton recommencer
        restartButton.addEventListener('click', initQuiz);
        
        // Fermer le modal
        closeModalButton.addEventListener('click', () => {
            resultModal.style.display = 'none';
            initQuiz();
        });
        
        // Initialiser le quiz au chargement
        window.addEventListener('load', initQuiz);
    </script>

