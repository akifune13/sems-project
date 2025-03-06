# School Enrollment Management System (SEMS)

## Overview
Welcome to the **School Enrollment Management System (SEMS)**, a project developed by **TechSavvy Tribes** for **Cavite National High School - Senior High School**. This system streamlines the enrollment process by managing student records, enrollment details, and other administrative tasks efficiently.

## Features
- Student Registration and Enrollment
- Enrollment Status
- User Authentication and Role-based Access

## Prerequisites
Before setting up the system, ensure you have the following installed:
- [XAMPP](https://www.apachefriends.org/download.html) (Apache, MySQL, PHP, and phpMyAdmin)
- A web browser (Google Chrome, Mozilla Firefox, etc.)
- A code editor (VS Code, Sublime Text, etc.)

## Database Setup Guide
Follow these steps to set up the database using **XAMPP**:

1. **Install XAMPP**
   - Download and install XAMPP from the official website.
   - Start **Apache** and **MySQL** from the XAMPP Control Panel.

2. **Create the Database**
   - Open your browser and go to `http://localhost/phpmyadmin`.
   - Click on the **Databases** tab.
   - Enter `db_admin` as the database name and click **Create**.

3. **Import the Database Schema**
   - Click on the **sems_db** database.
   - Go to the **Import** tab.
   - Click **Choose File** and select the SQL file (`sems_db.sql`) from the project folder.
   - Click **Go** to import the database structure and data.

4. **Configure the Database Connection**
   - Navigate to your project folder and open the configuration file (`config.php`).
   - Update the database connection settings:
     ```php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "sems_db";
     ```
   - Save the changes.

5. **Run the Project**
   - Move the project folder to `C:\xampp\htdocs\`.
   - Open your browser and go to `http://localhost/sems/`.
   - Log in using the provided credentials or register a new user.

## Contributors
- **TechSavvy Tribes**

## License
This project is for educational purposes only.
