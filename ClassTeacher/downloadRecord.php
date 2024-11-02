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

// Fetch class name for the class teacher from multiple databases
$rrw = ['className' => ''];
$classId = null;
foreach ($dbs as $dbKey) {
  $query = "SELECT tblclass.className, tblclassteacher.classId 
            FROM tblclassteacher
            INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
            WHERE tblclassteacher.Id = '$_SESSION[userId]'";
  $rs = $conn[$dbKey]->query($query);
  if ($rs && $rs->num_rows > 0) {
    $rrw = $rs->fetch_assoc();
    $classId = $rrw['classId'];
    break;
  }
}

if ($classId) {
    $filename = "Attendance list";
    $dateTaken = date("Y-m-d");

    $cnt = 1;
    $ret = $conn['sas_six']->query("SELECT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblclass.className,
            tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber
            FROM tblattendance
            INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
            INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
            WHERE tblattendance.dateTimeTaken = '$dateTaken' AND tblattendance.classId = '$classId'");

    if ($ret->num_rows > 0) {
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
    } else {
        echo "No records found for the selected date.";
    }
} else {
    echo "Class ID not found.";
}
?>
