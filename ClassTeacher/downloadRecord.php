<?php
// Include database connection
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Define the current date
$dateTaken = date("Y-m-d");

// Initialize database variables
$host = 'localhost:5222';
$user = 'root';
$pass = '';
$dbs = ['sas_six', 'sas_seven', 'sas_eight', 'sas_other'];

// Connect to the correct database based on class
$conn = [];
foreach ($dbs as $db) {
    $conn[$db] = new mysqli($host, $user, $pass, $db);
    if ($conn[$db]->connect_error) {
        die("Connection failed for $db: " . $conn[$db]->connect_error);
    }
}

// Find class ID from session email using prepared statements to prevent SQL injection
$classId = null;
$className = ''; // Initialize class name
foreach ($dbs as $dbKey) {
    $stmt = $conn[$dbKey]->prepare("SELECT tblclass.className, tblclassteacher.classId 
                                    FROM tblclassteacher
                                    INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
                                    WHERE tblclassteacher.emailAddress = ?");
    $stmt->bind_param("s", $_SESSION['emailAddress']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $rrw = $result->fetch_assoc();
        $classId = $rrw['classId'];
        $className = $rrw['className'];  // Fetch class name
        $dbKeyForAttendance = $dbKey;
        break;
    }
    $stmt->close();
}

// Ensure classId is found before proceeding
if ($classId === null) {
    die("Class ID not found for the given email.");
}

// Retrieve today’s attendance records
$stmt = $conn[$dbKeyForAttendance]->prepare("SELECT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, 
                                                    tblclass.className, tblstudents.firstName, tblstudents.lastName, 
                                                    tblstudents.otherName, tblstudents.admissionNumber
                                            FROM tblattendance
                                            INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                                            INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
                                            WHERE tblattendance.dateTimeTaken = ? AND tblattendance.classId = ?");
$stmt->bind_param("si", $dateTaken, $classId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $conn[$dbKeyForAttendance]->error);
}

// Set headers to download as Excel file
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"attendance_{$className}_{$dateTaken}.xls\"");
header("Pragma: no-cache");
header("Expires: 0");

// Create table structure for Excel file
echo "<table border='1'>";
echo "<tr>
        <th>#</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Other Name</th>
        <th>Admission No</th>
        <th>Class</th>
        <th>Status</th>
        <th>Date</th>
      </tr>";

// Output data rows
$sn = 0;
while ($row = $result->fetch_assoc()) {
    $sn++;
    $status = $row['status'] == '1' ? "Present" : "Absent";
    echo "<tr>
            <td>{$sn}</td>
            <td>{$row['firstName']}</td>
            <td>{$row['lastName']}</td>
            <td>{$row['otherName']}</td>
            <td>{$row['admissionNumber']}</td>
            <td>{$row['className']}</td>
            <td>{$status}</td>
            <td>{$row['dateTimeTaken']}</td>
          </tr>";
}

echo "</table>";

// Close database connections
foreach ($conn as $c) {
    $c->close();
}
?>
