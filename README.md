# School Attendance Management System

A distributed database-based School Attendance Management System designed to manage school attendance records efficiently. This project is built using PHP, HTML, CSS, and JavaScript and operates on multiple databases for scalability and organization.
- Project Proposal:
[View SAS Project Proposal PDF](https://github.com/0mehedihasan/sas/raw/main/Proposal%2CSlides%2CReports/SAS%20project%20proposal.pdf)
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

## Distributed Database Management System (DDBMS)
### ER Diagram 
[ER Diagram](https://github.com/0mehedihasan/sas/blob/main/Proposal%2CSlides%2CReports/ERdiagram.png)

### Diagram
![Distributed Database Management System Diagram](https://github.com/0mehedihasan/sas/blob/main/Proposal%2CSlides%2CReports/ddbms.drawio.png)
- This image contains an overview of a **Distributed Database Management System (DDBMS)** for a **Student Attendance System**. The DDBMS is designed to manage and partition data access for different classes, teachers, students, and administrators within a school setting.
### Architecture Overview
The DDBMS architecture is structured as follows:
#### 1. Central Database
- A single, centralized database where all data is stored.
- Accessible by the **Student Attendance System (SAS)**, which manages attendance records and user access across different classes.
- **Note:** Originally, the system was designed with a single, centralized database where all data would be stored and accessed by the Student Attendance System (SAS) for managing attendance records and user access across different classes. However, for enhanced efficiency and scalability, a distributed database architecture was implemented instead of a centralized one. This approach allows for better load distribution, faster data retrieval, and greater fault tolerance across the system.
#### 2. Student Attendance System (SAS)
- The main interface connecting teachers, students, and administrators with their relevant attendance data.
- Ensures partitioned access to the database, allowing each class to only access its specific data subset.
#### 3. Class-Specific Partitioned Databases
The database is partitioned based on class to enhance data security and control:
- **sas_six**: 
  - Accessible only by Class 6 teachers, students, and administrators.
  - Contains data specific to Class 6.
- **sas_seven**:
  - Accessible only by Class 7 teachers, students, and administrators.
  - Contains data specific to Class 7.
- **sas_eight**:
  - Accessible only by Class 8 teachers, students, and administrators.
  - Contains data specific to Class 8.
#### 4. General Database (sas_other)
- **sas_other**: A single database accessible by all classes **except Classes 6, 7, and 8**.
- Allows access to teachers, students, and administrators across these other classes.
### Data Access Control
- Each partitioned database is restricted to its respective class, providing enhanced data security by isolating access based on user role and class.
- The **sas_other** database is used as a consolidated resource for classes outside of Class 6, Class 7, and Class 8.
### Summary:
- This DDBMS setup improves data efficiency and security by ensuring that each segment of users can only access the data they are authorized to see. The structure allows the school to manage attendance records more effectively, with tailored access for each class.
---

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

## Example Code Snippet
Here's the dbcon.php code:
```php
<?php
// dbcon.php

// Database connection parameters
$host = "localhost:5222"; // Hostname with port number
$user = "root";           // Database username
$pass = "";               // Database password (empty in this case)

// List of database names to connect to
$dbs = [
    "sas_six",
    "sas_seven",
    "sas_eight",
    "sas_other"
];

// Array to hold the database connections
$conn = [];

// Create a connection for each database
foreach ($dbs as $dbName) {
    // Attempt to create a new database connection for the current database
    $dbConnection = new mysqli($host, $user, $pass, $dbName);
    
    // Check if the connection was successful
    if ($dbConnection->connect_error) {
        // If there is a connection error, stop the script and show an error message
        die("Connection failed for $dbName: " . $dbConnection->connect_error);
    }

    // If the connection is successful, store it in the $conn array with the database name as the key
    $conn[$dbName] = $dbConnection;
}

// At this point, $conn array holds active connections to each specified database
?>
```
**Explanation of Comments Added:**
- **Database Parameters**: Explains the purpose of each variable (`$host`, `$user`, `$pass`, and `$dbs`).
- **Connection Array**: Indicates that `$conn` is used to store the database connections.
- **Loop & Connection Creation**: Details the purpose of the `foreach` loop and the connection creation for each database.
- **Error Handling**: Describes the `if` statement that checks for connection errors and the `die()` function to stop the script if an error occurs.
- **Successful Connection Storage**: Notes that the connection is stored in `$conn` only if it is successful.

Here’s an example of how the project dynamically selects the database connection:
This code connects to multiple databases, checks if there is a record for a class teacher's `classId` based on the current `userId` from the session, and retrieves the associated `className`. 
```php
<?php
// Define the database connection variables
$host = 'localhost:5222'; // Hostname with port for the database server
$user = 'root';           // Username for database access
$pass = '';               // Password for database access (empty in this case)

// Define the list of databases to connect to
$dbs = ['sas_six', 'sas_seven', 'sas_eight', 'sas_other'];

// Initialize an empty array to store the database connections
$conn = [];

// Establish a connection to each database in the $dbs array
foreach ($dbs as $db) {
  // Create a new connection for each database and store it in the $conn array
  $conn[$db] = new mysqli($host, $user, $pass, $db);

  // Check if the connection was successful
  if ($conn[$db]->connect_error) {
    // If there is an error, stop the script and display an error message
    die("Connection failed for $db: " . $conn[$db]->connect_error);
  }
}

// Prepare an empty array to hold the fetched class data
$rrw = ['className' => ''];
$classId = null; // Variable to store the classId once found

// Loop through each database to execute the query
foreach ($dbs as $dbKey) {
  // SQL query to fetch class name and class ID where the class teacher matches the current user ID from the session
  $query = "SELECT tblclass.className, tblclassteacher.classId 
            FROM tblclassteacher
            INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
            WHERE tblclassteacher.Id = '".$_SESSION['userId']."'";

  // Execute the query on the current database connection
  $rs = $conn[$dbKey]->query($query);

  // Check if the query was successful and returned any rows
  if ($rs && $rs->num_rows > 0) {
    // Fetch the result as an associative array
    $rrw = $rs->fetch_assoc();
    // Store the class ID from the result
    $classId = $rrw['classId'];
    // Break out of the loop once data is found
    break;
  }
}

// At this point, $rrw contains the class name and $classId holds the class ID for the class teacher if found
?>
```
**Explanation of Comments Added**:
- **Database Parameters**: Clear explanations for each variable involved in the database connection (`$host`, `$user`, `$pass`, and `$dbs`).
- **Connection Array**: Explains the purpose of `$conn` to hold connections to each database.
- **Loop & Connection Creation**: Details the logic of creating a database connection for each database and checking for errors.
- **Class Data Retrieval**: Comments guide through the query execution for fetching the `className` and `classId` for the teacher's class from each database.
- **Loop Break**: Explains that the loop stops after the class data is found, improving efficiency by avoiding unnecessary queries on remaining databases.

# Output Screenshots
Here are some screenshots of the system in action:
## Login Page:
- Login Page
![Login Panel](https://github.com/0mehedihasan/sas/blob/main/snap/login.PNG)
## Admin Panel:
- Admin Dashboard
![Admin Panel](https://github.com/0mehedihasan/sas/blob/main/snap/admindashboard.PNG)
- Admin Create Class
![Admin Create Class](https://github.com/0mehedihasan/sas/blob/main/snap/admincreateclass.PNG)
- Admin Create Teacher
![Admin Create Class teacher](https://github.com/0mehedihasan/sas/blob/main/snap/admincreateteacher.PNG)
![Admin Create Class teache](https://github.com/0mehedihasan/sas/blob/main/snap/admincreateteacher1.PNG)
- Admin Create Student
![Admin Create Student](https://github.com/0mehedihasan/sas/blob/main/snap/admincreatestudent.PNG)
![Admin Create Student1](https://github.com/0mehedihasan/sas/blob/main/snap/admincreatestudent1.PNG)
## Teacher Panel:
- Teacher Dashboard
![Teacher Dashboard](https://github.com/0mehedihasan/sas/blob/main/snap/teacherdasboard.PNG)
- View Student List
![View Student List](https://github.com/0mehedihasan/sas/blob/main/snap/teacherstudentview.PNG)
- Take Attendance
![Take Attendance](https://github.com/0mehedihasan/sas/blob/main/snap/takeattendance.PNG)
![Take Attendance](https://github.com/0mehedihasan/sas/blob/main/snap/takeattendance1.PNG)
- View Attendance
![View Attendance](https://github.com/0mehedihasan/sas/blob/main/snap/viewattendance.PNG)
- Download Attendance
![Download Attendance](https://github.com/0mehedihasan/sas/blob/main/snap/viewattendancefile.PNG)

## Student Panel:
- Student Dashboard
![Student Dashboard](https://github.com/0mehedihasan/sas/blob/main/snap/studentdashboard.PNG)
- View Attendance
![View Attendance](https://github.com/0mehedihasan/sas/blob/main/snap/studentviewattendance.PNG)
- Download Attendance
![Download Attendance](https://github.com/0mehedihasan/sas/blob/main/snap/studentattendancefile.PNG)


### XAMPP & MySQL Workbench Clash <br>
Solution: https://www.youtube.com/watch?v=gxYpitQmais&t=502s&ab_channel=FahimAmin
