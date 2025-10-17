<?php
session_start();
require_once 'db.php';
require_once 'header.php';
require_once 'functions1.php';

// Fetch data for the page
$news = get_news($conn);
$posts = get_posts($conn);

$user_id = $_SESSION['id'] ?? null;
$username = ($_SESSION['prenom'] ?? '') . ' ' . ($_SESSION['nom'] ?? '');
$profile_pic = $_SESSION['profile_pic'] ?? 'default.jpg';
$level = $_SESSION['filiere'] ?? 'Étudiant';
?>

<?php if (isset($_SESSION['id'])): ?>
    <div class="main-content">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <h3 class="sidebar-title">Mon Profil</h3>
            <div class="user-card">
                <img src="uploads/<?= $profile_pic ?>" alt="Profile">
                <div class="user-info">
                    <div class="username"><?= $username ?></div>
                    <div class="level"><?= $level ?></div>
                </div>
            </div>
            <h3 class="sidebar-title">Navigation</h3>
            <div class="menu">
                <a href="profile.php" class="menu-item"><i class="fas fa-user"></i> Mon Profil</a>
                <a href="#" class="menu-item"><i class="fas fa-question-circle"></i> Aide</a>
            </div>
        </div>

        <!-- News Feed -->
        <div class="news-feed">
            <!-- Create Post Form -->
            <form class="post-form" method="POST" action="create_post.php" enctype="multipart/form-data">
                <textarea name="content" placeholder="Quoi de neuf, <?= $username ?> ?" required></textarea>
                <div class="file-upload">
                    <label for="post_file"><i class="fas fa-paperclip"></i> Joindre un fichier</label>
                    <input type="file" name="post_file" id="post_file" style="display: none;">
                    <span class="file-name" id="file_name">Aucun fichier sélectionné</span>
                </div>
                <button type="submit" name="create_post" class="btn btn-primary">Publier</button>
            </form>

            <!-- User Posts -->
            <h3 class="section-title">Publications récentes</h3>
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <div class="post-header">
                        <img src="uploads/<?= $post['photo_profil'] ?>" alt="Profile">
                        <div class="post-user">
                            <div class="username"><?= $post['username'] ?></div>
                            <div class="post-time"><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></div>
                        </div>
                    </div>

                    <div class="post-content">
                        <p><?= $post['content'] ?></p>
                        <?php if ($post['file_path']): ?>
                            <div class="post-file">
                                <?php 
                                    $ext = pathinfo($post['file_path'], PATHINFO_EXTENSION);
                                    $image_exts = ['jpg', 'jpeg', 'png', 'gif'];
                                ?>
                                <?php if (in_array(strtolower($ext), $image_exts)): ?>
                                    <img src="uploads/<?= $post['file_path'] ?>" alt="Post Image">
                                <?php else: ?>
                                    <a href="uploads/<?= $post['file_path'] ?>" class="file-download">
                                        <i class="fas fa-file-download"></i> Télécharger <?= strtoupper($ext) ?> (<?= $post['file_path'] ?>)
                                    </a>
                                <?php endif; ?>
                            </div>
                                <?php endif; ?>
                    </div>
                    <style>
                    .like-button.liked {
                            color: blue;
                    }

                    </style>


<div class="post-actions">
    <div class="post-action like-button" data-post-id="<?= $post['id'] ?>">
        <i class="<?= $post['user_has_liked'] ? 'fas' : 'far' ?> fa-thumbs-up"></i> 
        J'aime (<span class="like-count"><?= $post['like_count'] ?></span>)
    </div>
    <div class="post-action">
        <i class="far fa-comment"></i> Commenter
    </div>
    <div class="post-action">
        <i class="far fa-share-square"></i> Partager
    </div>
</div>



                    <div class="post-comments">
                        <h4 class="comments-title">Commentaires (<?= $post['comment_count'] ?>)</h4>

                        <?php foreach ($post['comments'] as $comment): ?>
                            <div class="comment">
                                <img src="uploads/<?= $comment['photo_profil'] ?>" alt="Profile">
                                <div class="comment-content">
                                    <div class="username"><?= $comment['username'] ?></div>
                                    <div class="text"><?= $comment['content'] ?></div>
                                    <div class="comment-time"><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <form class="comment-form" method="POST" action="create_comment.php">
                            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                            <input type="text" name="comment_content" placeholder="Ajouter un commentaire..." required>
                            <button type="submit" name="create_comment" class="btn btn-primary">Envoyer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Right Sidebar -->
        <div class="right-sidebar">
            <h3 class="section-title">Actualités du Département</h3>
            <?php foreach ($news as $item): ?>
                <div class="news-card">
                    <div class="news-title"><?= $item['title'] ?></div>
                    <div class="news-date">Publié le <?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></div>
                    <div class="news-content"><?= $item['content'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.news-feed').addEventListener('click', function(e) {
        if (e.target.closest('.like-button')) {
            const likeBtn = e.target.closest('.like-button');
            const postId = likeBtn.dataset.postId;

            fetch('like_post.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'post_id=' + postId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const likeCountSpan = likeBtn.querySelector('.like-count');
                    likeCountSpan.textContent = data.like_count;

                    const icon = likeBtn.querySelector('i');
                    if (data.user_has_liked) {
                        likeBtn.classList.add('liked');
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                    } else {
                        likeBtn.classList.remove('liked');
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                    }
                } else {
                    alert(data.message);
                }
            });
        }
    });
});
</script>

<?php else: ?>
    <!-- Landing Page for Non-Logged-in Users -->
    <div class="hero">
        <div class="hero-banner">
            <h1>Bienvenue sur AHN CONNECT</h1>
            <p>Connectez-vous pour rejoindre vos camarades, partager vos ressources et rester informés des actualités de votre département.</p>
            <div class="hero-actions">
                <center>
                    <a href="login.php" class="btn btn-outline">Se connecter</a>
                </center>
            </div>
        </div>
        <div class="features">
            <div class="feature-card">
                <i class="fas fa-users"></i>
                <h3 style="color: #707070;">Communauté Étudiante</h3>
                <p style="color: #707070;">Intégrez une communauté active d'étudiants et échangez sur vos projets, vos cours et vos expériences.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-book"></i>
                <h3 style="color: #707070;">Ressources Partagées</h3>
                <p style="color: #707070;">Accédez et partagez des ressources pédagogiques variées : cours, exercices, projets, etc.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-bullhorn"></i>
                <h3 style="color: #707070;">Actualités en Direct</h3>
                <p style="color: #707070;">Restez informé en temps réel des annonces essentielles, des événements et des résultats du département.</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>
