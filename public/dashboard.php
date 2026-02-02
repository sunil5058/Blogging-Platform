<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get all posts
try {
    $stmt = $conn->prepare("
        SELECT posts.*, users.username,
               (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC
    ");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>
<?php require '../includes/header.php';?>
    <div class="navbar">
        <div class="navbar-inner">
            <h2>BlogCMS</h2>
            <div class="navbar-right">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="dashboard-header">
            <h1>My Dashboard</h1>
            <div class="action-buttons">
                <a href="create.php" class="btn">Create New Post</a>
                <a href="read.php" class="btn">View All Posts</a>
                 <a href="myPosts.php" class="btn">My Posts</a>
                <a href="search.php" class="btn">Search Posts</a>
            </div>
        </div>
        
        <h2>Recent Posts</h2>
        
        <?php if (empty($posts)): ?>
            <p>No posts yet. <a href="create.php">Create your first post!</a></p>
        <?php else: ?>
            <div class="posts-grid">
                <?php foreach ($posts as $post): ?>
                    <div class="post-card">
                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="post-meta">
                            By <?php echo htmlspecialchars($post['username']); ?> 
                            on <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                            • <?php echo $post['comment_count']; ?> 💬 Comments
                        </p>
                        <p class="post-excerpt">
                            <?php echo htmlspecialchars(substr($post['content'], 0, 150)); ?>...
                        </p>
                        <div class="post-actions">
                            <a href="read.php?id=<?php echo $post['id']; ?>" class="btn-small">Read</a>
                            <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                                <a href="update.php?id=<?php echo $post['id']; ?>" class="btn-small">Edit</a>
                                <a href="delete.php?id=<?php echo $post['id']; ?>" 
                                   class="btn-small btn-delete" 
                                   onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>