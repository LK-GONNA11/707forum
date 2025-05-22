<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'security.php';

SecuritySystem::checkForThreats();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $author = isset($_POST['author']) ? sanitize($_POST['author']) : null;
    Forum::createPost(sanitize($_POST['content']), $author);
}

$posts = Forum::getPosts();

include 'includes/header.php';
?>

<div class="container">
    <header class="forum-header">
        <h1>Welcome to 707Forum</h1>
        <p class="tagline">The last bastion of free speech</p>
    </header>

    <section class="post-form">
        <h2>Create New Post</h2>
        <form method="POST">
            <div class="form-group">
                <label for="author">Name (optional):</label>
                <input type="text" id="author" name="author" maxlength="30" 
                       placeholder="Anonymous" class="form-control">
            </div>
            <div class="form-group">
                <label for="content">Your Message:</label>
                <textarea id="content" name="content" required maxlength="<?= MAX_POST_LENGTH ?>"
                          class="form-control" placeholder="Speak your mind freely..."></textarea>
            </div>
            <button type="submit" class="btn-post">Submit Post</button>
        </form>
    </section>

    <section class="posts-list">
        <h2>Recent Discussions</h2>
        <?php if(empty($posts)): ?>
            <p class="no-posts">No posts yet. Be the first to break the silence.</p>
        <?php else: ?>
            <?php foreach($posts as $post): ?>
            <article class="post">
                <header class="post-header">
                    <span class="author"><?= sanitize($post['author']) ?></span>
                    <span class="post-meta">
                        <?= date('M j, Y H:i', strtotime($post['created_at'])) ?>
                        <?php if(Admin::isAdmin()): ?>
                        | <a href="admin/delete_post.php?id=<?= $post['id'] ?>" class="delete-link">Delete</a>
                        <?php endif; ?>
                    </span>
                </header>
                <div class="post-content"><?= nl2br(sanitize($post['content'])) ?></div>
            </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
