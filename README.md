# Faceblog

Faceblog is a lightweight social blogging platform built with PHP and MySQL, following a clean MVC architecture with Dependency Injection and CQRS-inspired structure.  
The project runs locally using XAMPP and demonstrates common web app patterns such as authentication, routing, and dynamic content rendering.

---

## Tech Stack

- PHP 8+
- MySQL (via XAMPP)
- Bootstrap 5 & Bootstrap Icons
- HTML / CSS
- MVC + Front Controller Pattern
- Dependency Injection (Service Provider)

---

## Installation (XAMPP)

### 1. Clone the Repository
Clone this project into your XAMPP htdocs folder:
cd C:\xampp\htdocs
git clone https://github.com/serhiiKhokhlov/php-web-project.git

### 2. Start XAMPP
- Launch XAMPP Control Panel
- Start Apache and MySQL

### 3. Database Setup
- Open http://localhost/phpmyadmin and create a database named: faceblog

Import the following SQL (or run manually):
- ´create_schema.sql´
- ´insert_schema.sql´

### 4. Run the Application
- Open your browser and navigate to:
  [Open Faceblog](http://localhost/php-web-project)

If everything is configured correctly, the login or homepage should appear