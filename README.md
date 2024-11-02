# School Attendance Management System

A distributed database-based School Attendance Management System designed to manage school attendance records efficiently. This project is built using PHP, HTML, CSS, and JavaScript and operates on multiple databases for scalability and organization.

## Features

- **Admin Panel**:
  - Create, update, edit, and delete records for classes, teachers, and students.
- **Teacher Panel**:
  - View class student lists.
  - Take daily attendance.
  - View attendance records.
  - Download attendance records in Excel format.
- **Distributed Databases**:
  - Utilizes multiple databases (`sas_six`, `sas_seven`, `sas_eight`, `sas_other`) based on the grade level, allowing data separation and scalability.

## Tech Stack

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL (via XAMPP)
- **Local Server**: XAMPP

## Project Structure

The main project files are stored in the `htdocs` folder of XAMPP, and the system connects to the correct database depending on the login credentials.

## Setup and Installation

### Prerequisites

- [XAMPP](https://www.apachefriends.org/index.html) (to run Apache and MySQL locally)
- PHP 7.4+
- MySQL
- Composer (for dependency management)

### Installation Steps

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/0mehedihasan/sas.git
   ```

2. **Move Files to XAMPP Directory**:
   Copy the project folder to `xampp/htdocs`.

3. **Configure Database Connections**:
   Open `config.php` in the project root and set the database details for each distributed database.

   Example:
   ```php
   <?php
   function getDatabaseConnection($dbname) {
       $host = "localhost";
       $username = "root";
       $password = "";
       try {
           $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
           $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           return $conn;
       } catch (PDOException $e) {
           echo "Connection failed: " . $e->getMessage();
       }
   }
   ```

4. **Import Databases**:
   Import the SQL files for each database (`sas_six`, `sas_seven`, `sas_eight`, `sas_other`) in PHPMyAdmin to set up the initial structure.

5. **Install PhpSpreadsheet**:
   Navigate to the project directory and install PhpSpreadsheet for exporting attendance data to Excel:
   ```bash
   composer require phpoffice/phpspreadsheet
   ```

6. **Start XAMPP**:
   Launch XAMPP and start Apache and MySQL.

7. **Access the Project**:
   Open your browser and navigate to:
   ```
   http://localhost/school-attendance-management-system
   ```

## Usage

1. **Admin Login**:
   The admin can log in to manage classes, teachers, and students.

2. **Teacher Login**:
   Teachers can log in to take attendance, view records, and download attendance as Excel files.

3. **Attendance Export**:
   Teachers can download attendance records by selecting the download option, which generates an Excel file of the attendance data.

## Folder Structure

```
school-attendance-management-system/
├── config.php          # Database configuration file
├── index.php           # Login page
├── admin/              # Admin dashboard and functionality
├── teacher/            # Teacher dashboard and functionality
├── assets/             # CSS, JavaScript, and image files
└── attendance_export/  # Scripts for Excel export
```

## Contributing

Contributions are welcome! If you’d like to improve or add new features to this project, feel free to submit a pull request. Please make sure to update tests as appropriate.

## License

Distributed under the MIT License. See `LICENSE` for more information.

## Acknowledgments

- PhpSpreadsheet - For generating Excel files
- IEEE Resources - Guidance on best practices in database management

This structured README should provide clarity for anyone who wants to understand, set up, or contribute to your project on GitHub.
