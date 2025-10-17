<?php
session_start();
require_once 'db.php';
require_once 'header.php';
require_once 'functions.php';

if (!isset($_SESSION['id'])) {
    header("Location: accueil.php");
    exit();
}

// Récupération des données
$news = get_news($conn);
$posts = get_posts($conn); // doit retourner ['files'] pour chaque post

$user_id = $_SESSION['id'] ?? null;
$username = ($_SESSION['prenom'] ?? '') . ' ' . ($_SESSION['nom'] ?? '');
$profile_pic = $_SESSION['profile_pic'] ?? 'default.jpg';
$level = $_SESSION['filiere'] ?? 'Étudiant';
?>

<?php if (isset($_SESSION['id'])): ?>
<div class="main-content">

    <!-- Sidebar gauche -->
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
        <center>
        <div style="color: black;">
    <a href="profile.php" class="menu-item"><i class="fas fa-user"></i> Mon Profil</a>&nbsp;&nbsp;&nbsp;
    <a href="amis.php" class="menu-item"><i class="fas fa-user-friends"></i> Amis</a>&nbsp;&nbsp;&nbsp;
    <a href="chat.php" class="menu-item"><i class="fas fa-comments"></i> Discussion</a><br>
    <a href="apropos.php" class="menu-item"><i class="fas fa-building"></i> Visiter le département</a>
</div>

        </center>
        </div>
    </div>

    <!-- Fil d’actualité -->
    <div class="news-feed">
        <!-- Formulaire de création -->
        <form class="post-form" method="POST" action="create_post.php" enctype="multipart/form-data">
            <textarea name="content" placeholder="Quoi de neuf, <?= $username ?> ?" required></textarea>
            <div class="file-upload">
                <label for="post_files"><i class="fas fa-paperclip"></i> Joindre des fichiers</label>
                <input type="file" name="post_files[]" id="post_files" multiple style="display: none;">
                <span class="file-name" id="file_name">Aucun fichier sélectionné</span>
            </div>
            <button type="submit" name="create_post" class="btn btn-primary">Publier</button>
        </form>

        <!-- Publications -->
        <h3 class="section-title">Publications récentes</h3>
        <?php foreach ($posts as $post): ?>
        
           <div class="post-card">
                    <div class="post-header">
                        <a href="uploads/<?= $post['photo_profil'] ?>"><img src="uploads/<?= $post['photo_profil'] ?>" alt="Profile"></a>
                        <div class="post-user">
                            <div class="username"><?= $post['username'] ?></div>
                            <div class="post-time"><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></div>
                        </div>
                    </div>

            <div class="post-content">
                <p><strong><?= $post['content'] ?></strong></p>

                <!-- Affichage fichiers multiples -->
                <?php if (!empty($post['files'])): ?>
                    <?php foreach ($post['files'] as $file): ?>
                        <div class="post-file">
                            <?php 
                                $ext = pathinfo($file['file_path'], PATHINFO_EXTENSION);
                                $image_exts = ['jpg', 'jpeg', 'png', 'gif'];
                            ?>
                            <?php if (in_array(strtolower($ext), $image_exts)): ?>
                                <a href="uploads/<?= $file['file_path'] ?>" target="_blank">
                                    <img src="uploads/<?= $file['file_path'] ?>" alt="Post Image">
                                </a>
                            <?php else: ?>
                                <a href="uploads/<?= $file['file_path'] ?>" class="file-download" target="_blank">
                                    <i class="fas fa-file-download"></i> Télécharger <?= strtoupper($ext) ?> (<?= $file['file_name'] ?>)
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div class="post-actions">
                <div class="post-action like-button" data-post-id="<?= $post['id'] ?>">
                    <i class="<?= $post['user_has_liked'] ? 'fas' : 'far' ?> fa-thumbs-up"></i>
                    (<span class="like-count"><?= $post['like_count'] ?></span>)
                </div>
                <div class="post-action"><i class="far fa-comment"></i> Comment</div>
                
<?php
  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
  $share_url = $scheme . "://" . $_SERVER['HTTP_HOST'] . "/post.php?id=" . $post['id'];
?>
<div class="post-action share-button"
     data-post-id="<?= $post['id'] ?>"
     data-post-url="<?= htmlspecialchars($share_url, ENT_QUOTES) ?>">
  <i class="far fa-share-square"></i> Share
</div>
            </div>

            <!-- Commentaires -->
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
                    <button type="submit" name="create_comment" class="send-btn btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>


        <!-- Right Sidebar -->
        <div class="right-sidebar">
            <h3 class="section-title">Actualités</h3>
            <?php foreach ($news as $item): ?>
                <div class="news-card">
                    <div class="news-title"><?= $item['title'] ?></div>
                    <div class="news-date">Publié le <?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></div>
                    <div class="news-content"><?= $item['content'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
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

<script>
function copyToClipboardRobust(text) {
  // 1) Si Clipboard API dispo ET contexte sécurisé (HTTPS / localhost)
  if (navigator.clipboard && window.isSecureContext) {
    return navigator.clipboard.writeText(text);
  }

  // 2) Fallback compatible HTTP: execCommand('copy')
  return new Promise(function(resolve, reject) {
    try {
      const ta = document.createElement('textarea');
      ta.value = text;
      ta.setAttribute('readonly', '');
      ta.style.position = 'fixed';
      ta.style.left = '-9999px';
      document.body.appendChild(ta);
      ta.select();
      ta.setSelectionRange(0, ta.value.length);
      const ok = document.execCommand('copy');
      document.body.removeChild(ta);
      if (ok) resolve();
      else reject(new Error('execCommand copy failed'));
    } catch (e) {
      reject(e);
    }
  });
}

document.addEventListener('DOMContentLoaded', function() {
  const feed = document.querySelector('.news-feed');
  if (!feed) return;

  feed.addEventListener('click', function(e) {
    // --- LIKE ---
    const likeWrap = e.target.closest('.like-button');
    if (likeWrap) {
      e.preventDefault();
      const postId = likeWrap.dataset.postId;
      fetch('like_post.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'post_id=' + encodeURIComponent(postId)
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          const likeCountSpan = likeWrap.querySelector('.like-count');
          if (likeCountSpan) likeCountSpan.textContent = data.like_count;

          const icon = likeWrap.querySelector('i');
          if (data.user_has_liked) {
            likeWrap.classList.add('liked');
            if (icon) { icon.classList.remove('far'); icon.classList.add('fas'); }
          } else {
            likeWrap.classList.remove('liked');
            if (icon) { icon.classList.remove('fas'); icon.classList.add('far'); }
          }
        } else {
          alert(data.message || 'Une erreur est survenue.');
        }
      })
      .catch(() => alert('Erreur réseau.'));
      return;
    }

    // --- SHARE ---
    const shareBtn = e.target.closest('.share-button');
    if (shareBtn) {
      e.preventDefault();
      const url = shareBtn.dataset.postUrl || window.location.href;

      // 1) Web Share API (mobile/HTTPS)
      if (navigator.share) {
        navigator.share({
          title: "Publication AHN Connect",
          text: "Découvre ce post sur AHN Connect",
          url: url
        })
        .catch(() => {/* ignore cancel */});
        return;
      }

      // 2) Copie dans le presse-papier (HTTPS)
      copyToClipboardRobust(url)
        .then(() => alert("Lien copié dans le presse-papiers ✅\n" + url))
        .catch(() => {
          // 3) Prompt manuel (fonctionne partout)
          const manual = prompt("Copiez ce lien :", url);
          // même si l'utilisateur annule, on ne casse pas l’UX
        });
    }
  });
});
</script>

<?php require_once 'footer.php'; ?>

