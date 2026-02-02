<?php
session_start();
require_once '../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Username or email already exists!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password]);
                
                $success = "Registration successful! You can now login.";
            }
        } catch(PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>
<?php require '../includes/header.php';?>
    <div class="container">
        <h1>Register</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" onsubmit="return validateForm()">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" id="username" required>
                <span class="error-msg" id="username-error"></span>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" id="email" required>
                <span class="error-msg" id="email-error"></span>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" id="password" required>
                <span class="error-msg" id="password-error"></span>
            </div>
            
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <span class="error-msg" id="confirm-error"></span>
            </div>
            
            <button type="submit" class="btn">Register</button>
        </form>
        
        <p>Already have an account? <a href="index.php
        ">Login here</a></p>
    </div>
    
    <script>
        function validateForm() {
            var username = document.getElementById('username').value;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            var confirm = document.getElementById('confirm_password').value;
            var valid = true;
            
            document.getElementById('username-error').innerHTML = '';
            document.getElementById('email-error').innerHTML = '';
            document.getElementById('password-error').innerHTML = '';
            document.getElementById('confirm-error').innerHTML = '';
            
            if (username.length < 3) {
                document.getElementById('username-error').innerHTML = 'Username must be at least 3 characters';
                valid = false;
            }
            
            if (!email.includes('@')) {
                document.getElementById('email-error').innerHTML = 'Please enter a valid email';
                valid = false;
            }
            
            if (password.length < 6) {
                document.getElementById('password-error').innerHTML = 'Password must be at least 6 characters';
                valid = false;
            }
            
            if (password !== confirm) {
                document.getElementById('confirm-error').innerHTML = 'Passwords do not match';
                valid = false;
            }
            
            return valid;
        }
    </script>
</body>
</html>
