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