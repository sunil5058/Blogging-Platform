<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_comment'])) {
    $post_id = $_POST['post_id'];
    $comment = trim($_POST['comment']);
    
    if (!empty($comment)) {
        try {
            $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
            $stmt->execute([$post_id, $_SESSION['user_id'], $comment]);
            $success = "Comment added successfully!";
        } catch(PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Handle comment deletion
if (isset($_GET['delete_comment'])) {
    $comment_id = $_GET['delete_comment'];
    try {
        $stmt = $conn->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
        $stmt->execute([$comment_id, $_SESSION['user_id']]);
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Check if viewing single post
if (isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare("
            SELECT posts.*, users.username 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE posts.id = ?
        ");
        $stmt->execute([$_GET['id']]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get comments for this post
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
            
            // Get comment count
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM comments WHERE post_id = ?");
            $stmt->execute([$_GET['id']]);
            $comment_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        }
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
} else {
    // Get all posts with comment counts
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
}
?>

<?php require '../includes/header.php';?>
    <div class="navbar">
        <div class="navbar-inner">
            <h2>BlogCMS</h2>
            <div class="navbar-right">
                <a href="dashboard.php" class="btn-nav">Dashboard</a>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <?php if (isset($post)): ?>
            <!-- Single Post View -->
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
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </div>
                    <div class="post-actions">
                        <a href="read.php" class="btn">Back to All Posts</a>
                        <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                            <a href="update.php?id=<?php echo $post['id']; ?>" class="btn">Edit Post</a>
                            <a href="delete.php?id=<?php echo $post['id']; ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('Are you sure you want to delete this post?')">Delete Post</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Comments Section -->
                <div class="comments-section">
                    <h2>Comments (<?php echo $comment_count; ?>)</h2>
                    
                    <!-- Add Comment Form -->
                    <div class="comment-form">
                        <?php if (isset($success)): ?>
                            <div class="success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <div class="form-group">
                                <textarea name="comment" placeholder="Add a comment..." rows="3" required></textarea>
                            </div>
                            <button type="submit" name="add_comment" class="btn">Post Comment</button>
                        </form>
                    </div>
                    
                    <!-- Display Comments -->
                    <div class="comments-list">
                        <?php if (empty($comments)): ?>
                            <p class="no-comments">No comments yet. Be the first to comment!</p>
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
                                    <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                                        <a href="?id=<?php echo $post['id']; ?>&delete_comment=<?php echo $comment['id']; ?>" 
                                           class="comment-delete"
                                           onclick="return confirm('Delete this comment?')">Delete</a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <p>Post not found.</p>
            <?php endif; ?>
            
        <?php else: ?>
            <!-- All Posts View -->
            <h1>All Posts</h1>
            
            <?php if (empty($posts)): ?>
                <p>No posts available.</p>
            <?php else: ?>
                <table class="posts-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Comments</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                <td><?php echo htmlspecialchars($post['username']); ?></td>
                                <td><?php echo $post['comment_count']; ?> </td>
                                <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <a href="read.php?id=<?php echo $post['id']; ?>" class="btn-small">View</a>
                                    <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                                        <a href="update.php?id=<?php echo $post['id']; ?>" class="btn-small">Edit</a>
                                        <a href="delete.php?id=<?php echo $post['id']; ?>" 
                                           class="btn-small btn-delete" 
                                           onclick="return confirm('Are you sure?')">Delete</a>
                                    <?php endif; ?>
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