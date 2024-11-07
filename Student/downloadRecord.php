<?php 
error_reporting(0);
session_start();
include '../Includes/dbcon.php';
include '../Includes/session.php';

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
        die("Connection failed: " . $conn[$db]->connect_error);
    }
}

$statusMsg = ""; // Initialize the status message variable

// Get the student's admission number from the session
$admissionNumber = $_SESSION['admissionNumber'] ?? null;

if ($admissionNumber) {
    $filename = "Student Attendance";
    $dateTaken = date("Y-m-d");
    $cnt = 1;

    // Iterate over each database to fetch attendance records
    $foundRecords = false;
    foreach ($dbs as $dbKey) {
        $query = "SELECT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblclass.className,
                  tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber
                  FROM tblattendance
                  INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                  INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
                  WHERE tblattendance.dateTimeTaken = '$dateTaken' AND tblstudents.admissionNumber = '$admissionNumber'";
        
        $ret = $conn[$dbKey]->query($query);
        
        if ($ret && $ret->num_rows > 0) {
            $foundRecords = true;

            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=".$filename."-report.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo '<table border="1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Other Name</th>
                            <th>Admission No</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>';

            while ($row = $ret->fetch_assoc()) {
                $status = $row['status'] == '1' ? "Present" : "Absent";

                echo '<tr>
                        <td>'.$cnt.'</td>
                        <td>'.$row['firstName'].'</td>
                        <td>'.$row['lastName'].'</td>
                        <td>'.$row['otherName'].'</td>
                        <td>'.$row['admissionNumber'].'</td>
                        <td>'.$row['className'].'</td>
                        <td>'.$status.'</td>
                        <td>'.$row['dateTimeTaken'].'</td>
                    </tr>';
                $cnt++;
            }
            echo '</table>';
            break; // Stop after finding records in one database
        }
    }

    if (!$foundRecords) {
        echo "No attendance records found for today.";
    }
} else {
    echo "Admission number not found in session.";
}
?>
