<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare("
            SELECT posts.*, users.username 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE posts.id = ? AND posts.user_id = ?
        ");
        $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($post) {
            $stmt = $conn->prepare("
                SELECT comments.*, users.username 
                FROM comments 
                JOIN users ON comments.user_id = users.id 
                WHERE comments.post_id = ? 
                ORDER BY comments.created_at DESC
            ");
            $stmt->execute([$_GET['id']]);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM comments WHERE post_id = ?");
            $stmt->execute([$_GET['id']]);
            $comment_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        }
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
} else {
    try {
        $stmt = $conn->prepare("
            SELECT posts.*, users.username,
                   (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE posts.user_id = ?
            ORDER BY posts.created_at DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<?php require '../includes/header.php';?>
    <div class="navbar">
        <div class="navbar-inner">
            <h2>BlogCMS</h2>
            <div class="navbar-right">
                <a href="dashboard.php" class="btn">Dashboard</a>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <?php if (isset($post)): ?>
            <?php if ($post): ?>
                <div class="post-single">
                    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                    <p class="post-meta">
                        By <?php echo htmlspecialchars($post['username']); ?> 
                        on <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                        <?php if ($post['updated_at'] != $post['created_at']): ?>
                            (Updated: <?php echo date('M d, Y', strtotime($post['updated_at'])); ?>)
                        <?php endif; ?>
                    </p>
                    <div class="post-content">
                        <?php echo(htmlspecialchars($post['content'])); ?>
                    </div>
                    <div class="post-actions">
                        <a href="myPosts.php" class="btn">Back to My Posts</a>
                        <a href="update.php?id=<?php echo $post['id']; ?>" class="btn">Edit Post</a>
                        <a href="delete.php?id=<?php echo $post['id']; ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('Are you sure you want to delete this post?')">Delete Post</a>
                    </div>
                </div>
                
                <div class="comments-section">
                    <h2>Comments (<?php echo $comment_count; ?>)</h2>
                    
                    <div class="comments-list">
                        <?php if (empty($comments)): ?>
                            <p class="no-comments">No comments yet.</p>
                        <?php else: ?>
                            <?php foreach ($comments as $comment): ?>
                                <div class="comment-item">
                                    <div class="comment-header">
                                        <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                                        <span class="comment-time"><?php echo date('M d, Y - g:i A', strtotime($comment['created_at'])); ?></span>
                                    </div>
                                    <div class="comment-text">
                                        <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <p>Post not found.</p>
            <?php endif; ?>
            
        <?php else: ?>
            <h1>My Posts</h1>
            
            <?php if (empty($posts)): ?>
                <p>You haven't created any posts yet. <a href="create.php">Create your first post!</a></p>
            <?php else: ?>
                <table class="posts-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Comments</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                <td><?php echo $post['comment_count']; ?> </td>
                                <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <a href="myPosts.php?id=<?php echo $post['id']; ?>" class="btn-small">View</a>
                                    <a href="update.php?id=<?php echo $post['id']; ?>" class="btn-small">Edit</a>
                                    <a href="delete.php?id=<?php echo $post['id']; ?>" 
                                       class="btn-small btn-delete" 
                                       onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>