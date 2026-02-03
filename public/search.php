<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['ajax']) && isset($_GET['term'])) {

    $term = '%' . trim($_GET['term']) . '%';

    $stmt = $conn->prepare("
        SELECT posts.id, posts.title, users.username
        FROM posts
        JOIN users ON posts.user_id = users.id
        WHERE posts.title LIKE ? OR users.username LIKE ?
        ORDER BY posts.created_at DESC
        LIMIT 10
    ");
    $stmt->execute([$term, $term]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit();
}
?>

<?php require '../includes/header.php'; ?>

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
    <h1>AJAX Search</h1>

    <input 
        type="text" 
        id="search-input" 
        placeholder="Search by title or author"
        autocomplete="off"
    >

    <div id="results"></div>
</div>


<script>
document.getElementById("search-input").addEventListener("keyup", function () {

    let term = this.value;
    let resultsBox = document.getElementById("results");

    if (term.length < 2) {
        resultsBox.innerHTML = "";
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("GET", "search.php?ajax=1&term=" + term, true);

    xhr.onload = function () {
        let data = JSON.parse(this.responseText);
        let html = "";

        data.forEach(function (post) {
            html += `
                <div class="result-item">
                    <a href="read.php?id=${post.id}">
                        <strong>${post.title}</strong><br>
                        <small>by ${post.username}</small>
                    </a>
                </div>
            `;
        });

        resultsBox.innerHTML = html;
    };

    xhr.send();
});
</script>

</body>
</html>
