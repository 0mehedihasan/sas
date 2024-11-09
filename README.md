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
- **Student Panel**:
  - View attendance records.
  - Download attendance records in Excel format.
  - View personal information.
- **Distributed Databases**:
  - Utilizes multiple databases (`sas_six`, `sas_seven`, `sas_eight`, `sas_other`) based on the grade level, allowing data separation and scalability.

## Tech Stack

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL (via XAMPP)
- **Local Server**: XAMPP

## Project Structure

The main project files are stored in the `htdocs` folder of XAMPP, and the system connects to the correct database depending on the login credentials.

## Multi-Database Setup

### How It Works

The project is designed to handle multiple databases to manage different grade levels separately. This approach helps in organizing data efficiently and allows for better scalability. Here’s how it works:

1. **Database Connections**:
   - The project defines multiple databases (`sas_six`, `sas_seven`, `sas_eight`, `sas_other`) for different grade levels.
   - Each database connection is established at the beginning of the script using a loop that iterates through the list of databases.

2. **Dynamic Database Selection**:
   - Depending on the class or grade level, the system dynamically selects the appropriate database connection.
   - For example, when creating a new class or taking attendance, the system determines which database to use based on the class name or other criteria.

3. **Session Management**:
   - User sessions are managed to ensure that the correct database is accessed based on the logged-in user's role and class assignment.
   - The session variables store user information, including the user ID and class ID, which are used to fetch data from the correct database.

4. **Data Fetching and Insertion**:
   - Queries are executed on the selected database connection to fetch or insert data.
   - For example, when a teacher logs in, the system fetches the class information from the appropriate database based on the teacher's assigned class.

### Example Code Snippet

Here’s an example of how the project dynamically selects the database connection:

```php
// Define the database connection variables
$host = 'localhost:5222';
$user = 'root';
$pass = '';

// Define the databases
$dbs = ['sas_six', 'sas_seven', 'sas_eight', 'sas_other'];

// Define the database connections
$conn = [];
foreach ($dbs as $db) {
  $conn[$db] = new mysqli($host, $user, $pass, $db);
  if ($conn[$db]->connect_error) {
    die("Connection failed for $db: " . $conn[$db]->connect_error);
  }
}

// Fetch class name for the class teacher from multiple databases
$rrw = ['className' => ''];
$classId = null;
foreach ($dbs as $dbKey) {
  $query = "SELECT tblclass.className, tblclassteacher.classId 
            FROM tblclassteacher
            INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
            WHERE tblclassteacher.Id = '".$_SESSION['userId']."'";
  $rs = $conn[$dbKey]->query($query);
  if ($rs && $rs->num_rows > 0) {
    $rrw = $rs->fetch_assoc();
    $classId = $rrw['classId'];
    break;
  }
}
```
## Output Screenshots
Here are some screenshots of the system in action:
### Login Page
![Login Page](snap/login.png)

### Admin Panel
![Admin Panel](snap/admindashboard.png)
![Admin Create Class](snap/admincreateclass.png)
![Admin Create Class](snap/admincreateteacher.png)
![Admin Create Class](snap/admincreateteacher1.png)
![Admin Create Class](snap/admincreatestudent.png)
![Admin Create Class](snap/admincreatestudent1.png)
### Teacher Panel
![Teacher Panel](snap/teacherdashboard.png)

### Student Panel
![Student Panel](snap/studentdashboard.png)

### XAMPP & MySQL Workbench Clash <br>
Solution: https://www.youtube.com/watch?v=gxYpitQmais&t=502s&ab_channel=FahimAmin
