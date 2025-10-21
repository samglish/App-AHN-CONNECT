<!-- NAVIGATION.PHP -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ===================== */
/* BARRE DE NAVIGATION */
/* ===================== */
.navbar {
    background-color: #3b7ca7;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 13px;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 200;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    flex-wrap: nowrap;
}

/* ===================== */
/* MENU PRINCIPAL */
/* ===================== */
.nav-links {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    flex: 1;
}

.nav-links a {
    color: white;
    text-decoration: none;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: 0.2s;
}

.nav-links a:hover {
    color: #dceef7;
    transform: scale(1.05);
}

.nav-links a i {
    font-size: 20px;
}

/* ===================== */
/* PROFIL UTILISATEUR */
/* ===================== */
.user-section {
    display: flex;
    align-items: center;
    gap: 10px;
}

.profile-pic {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
}

.user-name {
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
}

.logout-btn {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    transition: 0.3s;
}

.logout-btn:hover {
    color: #ffdddd;
}

/* ===================== */
/* RESPONSIVE MOBILE */
/* ===================== */
@media screen and (max-width: 768px) {
    .navbar {
        flex-wrap: nowrap;
        justify-content: space-between;
        padding: 4px 6px; /* 🔹 un peu moins d’espace sur les bords */
    }

    .nav-links {
        flex: 1;
        justify-content: space-evenly; /* 🔹 répartit mieux les icônes */
        gap: 2px; /* 🔹 réduit encore l’espace entre icônes */
    }
    .nav-links a span {
    display: none;
}


    .nav-links a i {
        font-size: 21px; /* 🔹 taille légèrement réduite */
    }

    .user-section {
        gap: 6px; /* 🔹 rapproche photo et bouton power */
    }

    .logout-btn {
        font-size: 18px; /* 🔹 ajuste pour bien s’aligner */
    }
    
     .user-name {
        display: none; /* cache le nom sur mobile */
    }

}

/* pour éviter que le contenu soit caché sous la barre */
body {
    margin-top: 20px;
}
</style>

<div class="navbar">
    <!-- MENU DU SITE -->
    <nav class="nav-links">
        <a href="index.php" title="Accueil"><i class="fas fa-home"></i><span>Accueil</span></a>
        <a href="amis.php" title="Amis & Actus"><i class="fas fa-user-friends"></i><span>Amis/Actus</span></a>
        <a href="resultats.php" title="Résultats"><i class="fas fa-bell"></i><span>Résultats</span></a>
        <a href="messages.php" title="Discussions"><i class="fas fa-comments"></i><span>Discussions</span></a>
        <a href="library.php" title="Bibliothèque"><i class="fas fa-book"></i><span>Bibliothèque</span></a>
        <a href="apropos.php" title="À propos"><i class="fas fa-info-circle"></i><span>À propos</span></a>
    </nav>

    <!-- PROFIL À DROITE -->
    <?php if (isset($_SESSION['id'])): ?>
        <?php
            $username = $_SESSION['prenom'] . ' ' . $_SESSION['nom'];
            $profile_pic = $_SESSION['profile_pic'] ?? 'default.jpg';
        ?> 
        <div class="user-section">
            <img src="uploads/<?= $profile_pic ?>" alt="Profil" class="profile-pic">
            <span class="user-name"><?= htmlspecialchars($username) ?></span>
            <form action="logout.php" method="post" style="margin:0;">
                <button type="submit" class="logout-btn" title="Déconnexion">
                    <i class="fas fa-power-off"></i>
                </button>
            </form>
        </div>
    <?php else: ?>
        <div class="user-section">
            <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="register.php"><i class="fas fa-user-plus"></i> Register</a>
        </div>
    <?php endif; ?>
</div>

