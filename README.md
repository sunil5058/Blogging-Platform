## Topic: Blogging Platform / Content Management System (CMS)

A fully functional Blogging Platform / CMS developed using PHP, MySQL, HTML, CSS, and JavaScript.  
The application enables users to create, manage, and interact with blog content through a secure and user-friendly interface.


# Github Link
- https://github.com/sunil5058/Blogging-Platform.git


# Login Crediantial
- User1
Email :apple@gmail.com
Password :apple@123
- User2
Email :banana@gmail.com
Password :banana@123

- User3
Email :cherry@gmail.com
Password :cherry@123

- User4
Email :aalu@gmail.com
Password :aalu@123




## Setup Instructions

1. Install XAMPP and start Apache & MySQL.  

2. Copy the project folder into `C:\xampp\htdocs\`.  

3. Open `http://localhost/phpmyadmin`, create a database, and import the `.sql` file.  

4. Update database credentials in `db.php`.  

5. Open `http://localhost/` in the browser.


## Features

### CRUD Operations
- Create new blog posts  
- Read all posts or individual posts  
- Update existing posts  
- Delete posts with confirmation  

### Comments System
- Add comments to posts  
- View all comments per post  
- Delete your own comments  
- Display comment count  
- Separate comments table in database  

### Security
- SQL Injection prevention using prepared statements  
- XSS protection with `htmlspecialchars()`  
- Secure password hashing using `password_hash()`  
- Session-based authentication  
- Client-side and server-side validation 


# Issue
No Issue

