<?php
// configuration d base de donnee
$host = 'localhost';      // Server name
$dbname = 'ahnens9421_enspm';  //database name
$username = 'ahnens9421_sam';       
$password = 'Samglish12';           

// Create connection
try {
    $conn = mysqli_connect($host, $username, $password, $dbname);
    
    // Check connection
    if (!$conn) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }
    
    // Set UTF8MB4 charset for full Unicode support
    mysqli_set_charset($conn, "utf8mb4");
    
    // Crée les table s'il n'existe pas ( cameroon is bilingual)
    $table_queries = [
        "CREATE TABLE IF NOT EXISTS etudiants (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(50) NOT NULL,
            prenom VARCHAR(50) NOT NULL,
            matricule VARCHAR(20) NOT NULL,
            numero VARCHAR(20) NOT NULL,
            filiere VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            mot_de_passe VARCHAR(255) NOT NULL,
            profile_pic VARCHAR(255) DEFAULT 'default.jpg',
            bio TEXT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
        
        "CREATE TABLE IF NOT EXISTS posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            content TEXT NOT NULL,
            file_path VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
        
        "CREATE TABLE IF NOT EXISTS comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            user_id INT NOT NULL,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
        
        "CREATE TABLE IF NOT EXISTS department_news (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
    ];
    
    // Execute table creation queries
    foreach ($table_queries as $query) {
        if (!mysqli_query($conn, $query)) {
            error_log("Table creation error: " . mysqli_error($conn));
        }
    }
    
    // Insert sample news if not exists

    $news_check = "SELECT COUNT(*) AS count FROM department_news";
    $result = mysqli_query($conn, $news_check);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['count'] == 0) {
        $news_queries = [
            "INSERT INTO department_news (title, content) VALUES 
                ('Résultats ', 'Les résultats de la session normale et rattrapage sont disponibles sur la page résultats.')"
        ];
        
        foreach ($news_queries as $query) {
            mysqli_query($conn, $query);
        }
    }
    
} catch (Exception $e) {
    // Handle connection errors gracefully
    die("Database error: " . $e->getMessage());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
