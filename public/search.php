<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$results = [];
$search_term = '';

// Handle search
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = trim($_GET['search']);
    
    try {
        $stmt = $conn->prepare("
            SELECT posts.*, users.username,
                   (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE posts.title LIKE ? OR posts.content LIKE ?
            ORDER BY posts.created_at DESC
        ");
        $search_param = '%' . $search_term . '%';
        $stmt->execute([$search_param, $search_param]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Ajax autocomplete
if (isset($_GET['ajax']) && isset($_GET['term'])) {
    $term = trim($_GET['term']);
    
    try {
        $stmt = $conn->prepare("SELECT title FROM posts WHERE title LIKE ? LIMIT 5");
        $stmt->execute(['%' . $term . '%']);
        $suggestions = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        header('Content-Type: application/json');
        echo json_encode($suggestions);
        exit();
    } catch(PDOException $e) {
        echo json_encode([]);
        exit();
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
        <h1>Search Posts</h1>
        
        <form method="GET" action="" class="search-form">
            <div class="form-group">
                <input type="text" 
                       name="search" 
                       id="search-input" 
                       placeholder="Search by title or content..." 
                       value="<?php echo htmlspecialchars($search_term); ?>"
                       autocomplete="off">
                <div id="suggestions" class="suggestions"></div>
            </div>
            <button type="submit" class="btn">Search</button>
        </form>
        
        <?php if (isset($_GET['search'])): ?>
            <h2>Search Results for "<?php echo htmlspecialchars($search_term); ?>"</h2>
            
            <?php if (empty($results)): ?>
                <p>No posts found matching your search.</p>
            <?php else: ?>
                <p>Found <?php echo count($results); ?> post(s)</p>
                
                <div class="posts-grid">
                    <?php foreach ($results as $post): ?>
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
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <script>
        // Ajax autocomplete
        var searchInput = document.getElementById('search-input');
        var suggestionsDiv = document.getElementById('suggestions');
        var timeout = null;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            var term = this.value;
            
            if (term.length < 2) {
                suggestionsDiv.innerHTML = '';
                suggestionsDiv.style.display = 'none';
                return;
            }
            
            timeout = setTimeout(function() {
                // Make Ajax request
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'search.php?ajax=1&term=' + encodeURIComponent(term), true);
                
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var suggestions = JSON.parse(xhr.responseText);
                        
                        if (suggestions.length > 0) {
                            var html = '<ul>';
                            suggestions.forEach(function(suggestion) {
                                html += '<li onclick="selectSuggestion(\'' + suggestion.replace(/'/g, "\\'") + '\')">' + suggestion + '</li>';
                            });
                            html += '</ul>';
                            
                            suggestionsDiv.innerHTML = html;
                            suggestionsDiv.style.display = 'block';
                        } else {
                            suggestionsDiv.innerHTML = '';
                            suggestionsDiv.style.display = 'none';
                        }
                    }
                };
                
                xhr.send();
            }, 300);
        });
        
        function selectSuggestion(text) {
            searchInput.value = text;
            suggestionsDiv.innerHTML = '';
            suggestionsDiv.style.display = 'none';
        }
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target !== searchInput) {
                suggestionsDiv.innerHTML = '';
                suggestionsDiv.style.display = 'none';
            }
        });
    </script>
</body>
</html>