<nav class="nav-links">
    <a href="index.php"><i class="fas fa-home"></i>Home</a>
    <a href="news.php"><i class="fas fa-user-friends"></i> Actualités</a>
    <a href="resultats.php"><i class="fas fa-bell"></i> Résultats</a>
    <a href="chat.php"><i class="fas fa-newspaper"></i>💬 Chat</a>
</nav>
<style>
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
    gap: 8px;
}

.profile-pic {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
}

.profile-menu {
    display: none;
    position: absolute;
    right: 0;
    top: 40px;
    background-color:rgb(59, 124, 167);
    border-radius: 5px;
    overflow: hidden;
    flex-direction: column;
    min-width: 150px;
}

.profile-menu a {
    display: block;
    padding: 10px;
    color: white;
    text-decoration: none;
}

.profile-menu a:hover {
    background-color: #555;
}

/* Affichage sur mobile */
@media (max-width: 768px) {
    .profile-menu {
        position: static;
        width: 100%;
        border-radius: 0;
    }
}
.like-button.liked {
    color: blue;
}

</style>
<div class="user-actions">
    <?php if (isset($_SESSION['id'])): ?>
        <?php
        $username = $_SESSION['prenom'] . ' ' . $_SESSION['nom'];
        $profile_pic = $_SESSION['profile_pic'] ?? 'default.jpg';
        ?>
        <div class="profile-dropdown">
            <button class="profile-toggle" onclick="toggleProfileMenu()">
                <img src="uploads/<?= $profile_pic ?>" alt="Profile" class="profile-pic">
                <span><?= $username ?></span>
                <i class="fas fa-caret-down"></i>
            </button>
            <div class="profile-menu" id="profileMenu">
                <h5><a href="profile.php">Votre profil</a></h5>
               <h5><a href="logout.php">Deconnexion ⏻</a></h5>
            </div>
        </div>
    <?php else: ?>
       <a href="login.php" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login</a>
       <a href="register.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Register</a>
    <?php endif; ?>
</div>
<script>
function toggleProfileMenu() {
    document.getElementById('profileMenu').classList.toggle('show');
}
</script>

<style>
    .profile-menu.show {
        display: flex;
    }
</style>
