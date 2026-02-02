<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$post_id = $_GET['id'];
$error = '';
$success = '';

try {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$post_id, $_SESSION['user_id']]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$post) {
        header("Location: dashboard.php");
        exit();
    }
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token!";
    } else {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        
        if (empty($title) || empty($content)) {
            $error = "Title and content are required!";
        } elseif (strlen($title) < 5) {
            $error = "Title must be at least 5 characters!";
        } elseif (strlen($content) < 20) {
            $error = "Content must be at least 20 characters!";
        } else {
            try {
                $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$title, $content, $post_id, $_SESSION['user_id']]);
                
                $success = "Post updated successfully!";
                $post['title'] = $title;
                $post['content'] = $content;
                
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            } catch(PDOException $e) {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<?php require '../includes/header.php';?>
    <div class="navbar">
        <h2>Blog CMS</h2>
        <div>
            <a href="dashboard.php" class="btn-nav">Dashboard</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <h1>Update Post</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success">
                <?php echo htmlspecialchars($success); ?>
                <a href="read.php?id=<?php echo $post_id; ?>">View Post</a>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" onsubmit="return validatePost()">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" id="title" required 
                       value="<?php echo htmlspecialchars($post['title']); ?>">
                <span class="error-msg" id="title-error"></span>
            </div>
            
            <div class="form-group">
                <label>Content:</label>
                <textarea name="content" id="content" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                <span class="error-msg" id="content-error"></span>
            </div>
            
            <button type="submit" class="btn">Update Post</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    
    <script>
        function validatePost() {
            var title = document.getElementById('title').value;
            var content = document.getElementById('content').value;
            var valid = true;
            
            document.getElementById('title-error').innerHTML = '';
            document.getElementById('content-error').innerHTML = '';
            
            if (title.length < 5) {
                document.getElementById('title-error').innerHTML = 'Title must be at least 5 characters';
                valid = false;
            }
            
            if (content.length < 20) {
                document.getElementById('content-error').innerHTML = 'Content must be at least 20 characters';
                valid = false;
            }
            
            return valid;
        }
    </script>
</body>
</html>