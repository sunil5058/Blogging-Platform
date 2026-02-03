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