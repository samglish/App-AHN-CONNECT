<!-- NAVIGATION.PHP -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Style de base */
.navbar {
    background-color: #3b7ca7;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    position: relative;
}

.nav-links {
    display: flex;
    gap: 20px;
    align-items: center;
}

.nav-links a {
    color: white;
    text-decoration: none;
    font-size: 16px;
}

.nav-links a i {
    margin-right: 5px;
}

/* Profil utilisateur */
.user-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.profile-dropdown {
    position: relative;
}

.profile-toggle {
    display: flex;
    align-items: center;
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    gap: 5px;
    font-size: 14px;
}

.profile-pic {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
}

/* Dropdown menu */
.profile-menu {
    display: none;
    position: absolute;
    right: 0;
    top: 40px;
    background-color: #3b7ca7;
    border-radius: 5px;
    overflow: hidden;
    flex-direction: column;
    min-width: 150px;
    z-index: 100;
}

.profile-menu a {
    display: block;
    padding: 10px;
    color: white;
    text-decoration: none;
    font-size: 14px;
}

.profile-menu a:hover {
    background-color: #2d5e7f;
}

/* Burger icon */
.burger {
    display: none;
    font-size: 24px;
    cursor: pointer;
}

/* Responsive */
@media screen and (max-width: 768px) {
    .nav-links {
        display: none;
        flex-direction: column;
        position: absolute;
        top: 50px;
        left: 0;
        width: 100%;
        background-color: #3b7ca7;
        padding: 10px 0;
    }

    .nav-links.show {
        display: flex;
    }

    .burger {
        display: block;
    }

    .user-actions {
        flex-direction: column;
        align-items: flex-start;
    }

    .profile-menu {
        position: static;
        width: 100%;
        border-radius: 0;
    }
}

.profile-menu.show {
    display: flex;
}
</style>

<div class="navbar">
    <!-- Menu burger -->
    <i class="fas fa-bars burger" onclick="toggleNavMenu()"></i>

    <!-- Liens de navigation -->
    <nav class="nav-links" id="navMenu">
        <a href="index.php"><i class="fas fa-home"></i>Accueil</a>
        <a href="news.php"><i class="fas fa-user-friends"></i>Amis/Actus</a>
        <a href="resultats.php"><i class="fas fa-bell"></i>Résultats</a>
        <a href="chat.php"><i class="fas fa-comments"></i>Discussions</a>
        <a href="library.php"><i class="fas fa-book"></i> Bibliothèque</a>
        <a href="apropos.php"><i class="fas fa-info-circle"></i> À propos</a>
    </nav>

    <!-- Actions utilisateur -->
    <div class="user-actions">
        <?php if (isset($_SESSION['id'])): ?>
            <?php
                $username = $_SESSION['prenom'] . ' ' . $_SESSION['nom'];
                $profile_pic = $_SESSION['profile_pic'] ?? 'default.jpg';
            ?>
            <div class="profile-dropdown">
                <button class="profile-toggle" onclick="toggleProfileMenu()">
                    <img src="uploads/<?= $profile_pic ?>" alt="Profil" class="profile-pic">
                    <span><?= $username ?></span>
                    <i class="fas fa-caret-down"></i>
                </button>
                <div class="profile-menu" id="profileMenu">
                    <a href="profile.php">👤 Votre profil</a>
                    <a href="logout.php">⏻ Déconnexion</a>
                </div>
            </div>
        <?php else: ?>
            <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="register.php"><i class="fas fa-user-plus"></i> Register</a>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleProfileMenu() {
    document.getElementById('profileMenu').classList.toggle('show');
}

function toggleNavMenu() {
    document.getElementById('navMenu').classList.toggle('show');
}
</script>

